<?php

namespace Illuminate\Database;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDOException;
use Throwable;

class QueryException extends PDOException
{





public $connectionName;






protected $sql;






protected $bindings;






public $readWriteType;






protected $connectionDetails = [];











public function __construct($connectionName, $sql, array $bindings, Throwable $previous, array $connectionDetails = [], $readWriteType = null)
{
parent::__construct('', 0, $previous);

$this->connectionName = $connectionName;
$this->sql = $sql;
$this->bindings = $bindings;
$this->connectionDetails = $connectionDetails;
$this->readWriteType = $readWriteType;
$this->code = $previous->getCode();
$this->message = $this->formatMessage($connectionName, $sql, $bindings, $previous);

if ($previous instanceof PDOException) {
$this->errorInfo = $previous->errorInfo;
}
}










protected function formatMessage($connectionName, $sql, $bindings, Throwable $previous)
{
$details = $this->formatConnectionDetails();

return $previous->getMessage().' (Connection: '.$connectionName.$details.', SQL: '.Str::replaceArray('?', $bindings, $sql).')';
}






protected function formatConnectionDetails()
{
if (empty($this->connectionDetails)) {
return '';
}

$driver = $this->connectionDetails['driver'] ?? '';

$segments = [];

if ($driver !== 'sqlite') {
if (! empty($this->connectionDetails['unix_socket'])) {
$segments[] = 'Socket: '.$this->connectionDetails['unix_socket'];
} else {
$host = $this->connectionDetails['host'] ?? '';

$segments[] = 'Host: '.(is_array($host) ? implode(', ', $host) : $host);
$segments[] = 'Port: '.($this->connectionDetails['port'] ?? '');
}
}

$segments[] = 'Database: '.($this->connectionDetails['database'] ?? '');

return ', '.implode(', ', $segments);
}






public function getConnectionName()
{
return $this->connectionName;
}






public function getSql()
{
return $this->sql;
}




public function getRawSql(): string
{
return DB::connection($this->getConnectionName())
->getQueryGrammar()
->substituteBindingsIntoRawSql($this->getSql(), $this->getBindings());
}






public function getBindings()
{
return $this->bindings;
}






public function getConnectionDetails()
{
return $this->connectionDetails;
}
}
