<?php

namespace Illuminate\Cache;

use Illuminate\Database\Connection;
use Illuminate\Database\DetectsConcurrencyErrors;
use Illuminate\Database\QueryException;
use Throwable;

class DatabaseLock extends Lock
{
use DetectsConcurrencyErrors;






protected $connection;






protected $table;






protected $lottery;






protected $defaultTimeoutInSeconds;












public function __construct(Connection $connection, $table, $name, $seconds, $owner = null, $lottery = [2, 100], $defaultTimeoutInSeconds = 86400)
{
parent::__construct($name, $seconds, $owner);

$this->connection = $connection;
$this->table = $table;
$this->lottery = $lottery;
$this->defaultTimeoutInSeconds = $defaultTimeoutInSeconds;
}








public function acquire()
{
try {
$this->connection->table($this->table)->insert([
'key' => $this->name,
'owner' => $this->owner,
'expiration' => $this->expiresAt(),
]);

$acquired = true;
} catch (QueryException) {
$updated = $this->connection->table($this->table)
->where('key', $this->name)
->where(function ($query) {
return $query->where('owner', $this->owner)->orWhere('expiration', '<=', $this->currentTime());
})->update([
'owner' => $this->owner,
'expiration' => $this->expiresAt(),
]);

$acquired = $updated >= 1;
}

if (count($this->lottery ?? []) === 2 && random_int(1, $this->lottery[1]) <= $this->lottery[0]) {
$this->pruneExpiredLocks();
}

return $acquired;
}






protected function expiresAt()
{
$lockTimeout = $this->seconds > 0 ? $this->seconds : $this->defaultTimeoutInSeconds;

return $this->currentTime() + $lockTimeout;
}








public function release()
{
if ($this->isOwnedByCurrentProcess()) {
try {
$this->connection->table($this->table)
->where('key', $this->name)
->where('owner', $this->owner)
->delete();

return true;
} catch (Throwable $e) {
if ($this->causedByConcurrencyError($e)) {
return true;
}

throw $e;
}
}

return false;
}






public function forceRelease()
{
$this->connection->table($this->table)
->where('key', $this->name)
->delete();
}








public function pruneExpiredLocks()
{
try {
$this->connection->table($this->table)
->where('expiration', '<=', $this->currentTime())
->delete();
} catch (Throwable $e) {
if (! $this->causedByConcurrencyError($e)) {
throw $e;
}
}
}






protected function getCurrentOwner()
{
return $this->connection->table($this->table)->where('key', $this->name)->first()?->owner;
}






public function getConnectionName()
{
return $this->connection->getName();
}
}
