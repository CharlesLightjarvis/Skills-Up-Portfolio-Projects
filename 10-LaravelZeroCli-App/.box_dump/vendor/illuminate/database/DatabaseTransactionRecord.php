<?php

namespace Illuminate\Database;

class DatabaseTransactionRecord
{





public $connection;






public $level;






public $parent;






protected $callbacks = [];






protected $callbacksForRollback = [];








public function __construct($connection, $level, ?DatabaseTransactionRecord $parent = null)
{
$this->connection = $connection;
$this->level = $level;
$this->parent = $parent;
}







public function addCallback($callback)
{
$this->callbacks[] = $callback;
}







public function addCallbackForRollback($callback)
{
$this->callbacksForRollback[] = $callback;
}






public function executeCallbacks()
{
foreach ($this->callbacks as $callback) {
$callback();
}
}






public function executeCallbacksForRollback()
{
foreach ($this->callbacksForRollback as $callback) {
$callback();
}
}






public function getCallbacks()
{
return $this->callbacks;
}






public function getCallbacksForRollback()
{
return $this->callbacksForRollback;
}
}
