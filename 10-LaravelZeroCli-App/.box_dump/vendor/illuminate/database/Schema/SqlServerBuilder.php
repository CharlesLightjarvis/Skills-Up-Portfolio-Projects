<?php

namespace Illuminate\Database\Schema;

use Illuminate\Support\Arr;

class SqlServerBuilder extends Builder
{





public function dropAllTables()
{
$this->connection->statement($this->grammar->compileDropAllForeignKeys());

$this->connection->statement($this->grammar->compileDropAllTables());
}






public function dropAllViews()
{
$this->connection->statement($this->grammar->compileDropAllViews());
}






public function getCurrentSchemaName()
{
return Arr::first($this->getSchemas(), fn ($schema) => $schema['default'])['name'];
}
}
