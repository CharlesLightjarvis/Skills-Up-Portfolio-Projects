<?php

namespace Illuminate\Database\Events;

class QueryExecuted
{





public $sql;






public $bindings;






public $time;






public $connection;






public $connectionName;






public $readWriteType;










public function __construct($sql, $bindings, $time, $connection, $readWriteType = null)
{
$this->sql = $sql;
$this->time = $time;
$this->bindings = $bindings;
$this->connection = $connection;
$this->connectionName = $connection->getName();
$this->readWriteType = $readWriteType;
}






public function toRawSql()
{
return $this->connection
->query()
->getGrammar()
->substituteBindingsIntoRawSql($this->sql, $this->connection->prepareBindings($this->bindings));
}
}
