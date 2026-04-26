<?php

namespace Illuminate\Database\Schema;

class MySqlBuilder extends Builder
{





public function dropAllTables()
{
$tables = $this->getTableListing($this->getCurrentSchemaListing());

if (empty($tables)) {
return;
}

$this->disableForeignKeyConstraints();

try {
$this->connection->statement(
$this->grammar->compileDropAllTables($tables)
);
} finally {
$this->enableForeignKeyConstraints();
}
}






public function dropAllViews()
{
$views = array_column($this->getViews($this->getCurrentSchemaListing()), 'schema_qualified_name');

if (empty($views)) {
return;
}

$this->connection->statement(
$this->grammar->compileDropAllViews($views)
);
}






public function getCurrentSchemaListing()
{
return [$this->connection->getDatabaseName()];
}
}
