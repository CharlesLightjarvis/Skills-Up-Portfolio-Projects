<?php

namespace Illuminate\Database;

use Illuminate\Database\Query\Grammars\MariaDbGrammar as QueryGrammar;
use Illuminate\Database\Query\Processors\MariaDbProcessor;
use Illuminate\Database\Schema\Grammars\MariaDbGrammar as SchemaGrammar;
use Illuminate\Database\Schema\MariaDbBuilder;
use Illuminate\Database\Schema\MariaDbSchemaState;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MariaDbConnection extends MySqlConnection
{



public function getDriverTitle()
{
return 'MariaDB';
}






public function isMaria()
{
return true;
}






public function getServerVersion(): string
{
return Str::between(parent::getServerVersion(), '5.5.5-', '-MariaDB');
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

return new MariaDbBuilder($this);
}






protected function getDefaultSchemaGrammar()
{
return new SchemaGrammar($this);
}








public function getSchemaState(?Filesystem $files = null, ?callable $processFactory = null)
{
return new MariaDbSchemaState($this, $files, $processFactory);
}






protected function getDefaultPostProcessor()
{
return new MariaDbProcessor;
}
}
