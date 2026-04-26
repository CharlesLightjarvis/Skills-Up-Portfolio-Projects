<?php

namespace Illuminate\Cache\Limiters;

use Illuminate\Support\Sleep;
use Illuminate\Support\Str;
use Throwable;

class ConcurrencyLimiter
{





protected $store;






protected $name;






protected $maxLocks;






protected $releaseAfter;









public function __construct($store, $name, $maxLocks, $releaseAfter)
{
$this->name = $name;
$this->store = $store;
$this->maxLocks = $maxLocks;
$this->releaseAfter = $releaseAfter;
}












public function block($timeout, $callback = null, $sleep = 250)
{
$starting = time();

$id = Str::random(20);

while (! $slot = $this->acquire($id)) {
if (time() - $timeout >= $starting) {
throw new LimiterTimeoutException;
}

Sleep::usleep($sleep * 1000);
}

if (is_callable($callback)) {
try {
return tap($callback(), function () use ($slot) {
$this->release($slot);
});
} catch (Throwable $exception) {
$this->release($slot);

throw $exception;
}
}

return true;
}







protected function acquire($id)
{
for ($i = 1; $i <= $this->maxLocks; $i++) {
$lock = $this->store->lock($this->name.$i, $this->releaseAfter, $id);

if ($lock->acquire()) {
return $lock;
}
}

return false;
}







protected function release($lock)
{
$lock->release();
}
}
