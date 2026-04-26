<?php

namespace Illuminate\Database\Schema;

use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class SQLiteBuilder extends Builder
{






public function createDatabase($name)
{
return File::put($name, '') !== false;
}







public function dropDatabaseIfExists($name)
{
return ! File::exists($name) || File::delete($name);
}


public function getTables($schema = null)
{
try {
$withSize = $this->connection->scalar($this->grammar->compileDbstatExists());
} catch (QueryException) {
$withSize = false;
}

if (version_compare($this->connection->getServerVersion(), '3.37.0', '<')) {
$schema ??= array_column($this->getSchemas(), 'name');

$tables = [];

foreach (Arr::wrap($schema) as $name) {
$tables = array_merge($tables, $this->connection->selectFromWriteConnection(
$this->grammar->compileLegacyTables($name, $withSize)
));
}

return $this->connection->getPostProcessor()->processTables($tables);
}

return $this->connection->getPostProcessor()->processTables(
$this->connection->selectFromWriteConnection(
$this->grammar->compileTables($schema, $withSize)
)
);
}


public function getViews($schema = null)
{
$schema ??= array_column($this->getSchemas(), 'name');

$views = [];

foreach (Arr::wrap($schema) as $name) {
$views = array_merge($views, $this->connection->selectFromWriteConnection(
$this->grammar->compileViews($name)
));
}

return $this->connection->getPostProcessor()->processViews($views);
}


public function getColumns($table)
{
[$schema, $table] = $this->parseSchemaAndTable($table);

$table = $this->connection->getTablePrefix().$table;

return $this->connection->getPostProcessor()->processColumns(
$this->connection->selectFromWriteConnection($this->grammar->compileColumns($schema, $table)),
$this->connection->scalar($this->grammar->compileSqlCreateStatement($schema, $table))
);
}






public function dropAllTables()
{
foreach ($this->getCurrentSchemaListing() as $schema) {
$database = $schema === 'main'
? $this->connection->getDatabaseName()
: (array_column($this->getSchemas(), 'path', 'name')[$schema] ?: ':memory:');

if ($database !== ':memory:' &&
! str_contains($database, '?mode=memory') &&
! str_contains($database, '&mode=memory')
) {
$this->refreshDatabaseFile($database);
} else {
$this->pragma('writable_schema', 1);

$this->connection->statement($this->grammar->compileDropAllTables($schema));

$this->pragma('writable_schema', 0);

$this->connection->statement($this->grammar->compileRebuild($schema));
}
}
}






public function dropAllViews()
{
foreach ($this->getCurrentSchemaListing() as $schema) {
$this->pragma('writable_schema', 1);

$this->connection->statement($this->grammar->compileDropAllViews($schema));

$this->pragma('writable_schema', 0);

$this->connection->statement($this->grammar->compileRebuild($schema));
}
}








public function pragma($key, $value = null)
{
return is_null($value)
? $this->connection->scalar($this->grammar->pragma($key))
: $this->connection->statement($this->grammar->pragma($key, $value));
}







public function refreshDatabaseFile($path = null)
{
file_put_contents($path ?? $this->connection->getDatabaseName(), '');
}






public function getCurrentSchemaListing()
{
return ['main'];
}
}
