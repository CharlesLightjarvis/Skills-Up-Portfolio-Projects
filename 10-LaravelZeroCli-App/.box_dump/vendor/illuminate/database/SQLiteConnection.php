<?php

namespace Illuminate\Database;

use Exception;
use Illuminate\Database\Query\Grammars\SQLiteGrammar as QueryGrammar;
use Illuminate\Database\Query\Processors\SQLiteProcessor;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar as SchemaGrammar;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Database\Schema\SqliteSchemaState;
use Illuminate\Filesystem\Filesystem;

class SQLiteConnection extends Connection
{



public function getDriverTitle()
{
return 'SQLite';
}






protected function executeBeginTransactionStatement()
{
if (version_compare(PHP_VERSION, '8.4.0', '>=')) {
$mode = $this->getConfig('transaction_mode') ?? 'DEFERRED';

$this->getPdo()->exec("BEGIN {$mode} TRANSACTION");

return;
}

$this->getPdo()->beginTransaction();
}







protected function escapeBinary($value)
{
$hex = bin2hex($value);

return "x'{$hex}'";
}







protected function isUniqueConstraintError(Exception $exception)
{
return (bool) preg_match('#(column(s)? .* (is|are) not unique|UNIQUE constraint failed: .*)#i', $exception->getMessage());
}






protected function getDefaultQueryGrammar()
{
return new QueryGrammar($this);
}






public function getSchemaBuilder()
{
if (is_null($this->schemaGrammar)) {
$this->useDefaultSchemaGrammar();
}

return new SQLiteBuilder($this);
}






protected function getDefaultSchemaGrammar()
{
return new SchemaGrammar($this);
}









public function getSchemaState(?Filesystem $files = null, ?callable $processFactory = null)
{
return new SqliteSchemaState($this, $files, $processFactory);
}






protected function getDefaultPostProcessor()
{
return new SQLiteProcessor;
}
}
