<?php

namespace Illuminate\Database\Schema;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Database\Connection;
use Illuminate\Database\PostgresConnection;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use LogicException;
use RuntimeException;

class Builder
{
use Macroable;






protected $connection;






protected $grammar;






protected $resolver;






public static $defaultStringLength = 255;




public static ?int $defaultTimePrecision = 0;






public static $defaultMorphKeyType = 'int';






public function __construct(Connection $connection)
{
$this->connection = $connection;
$this->grammar = $connection->getSchemaGrammar();
}







public static function defaultStringLength($length)
{
static::$defaultStringLength = $length;
}




public static function defaultTimePrecision(?int $precision): void
{
static::$defaultTimePrecision = $precision;
}









public static function defaultMorphKeyType(string $type)
{
if (! in_array($type, ['int', 'uuid', 'ulid'])) {
throw new InvalidArgumentException("Morph key type must be 'int', 'uuid', or 'ulid'.");
}

static::$defaultMorphKeyType = $type;
}






public static function morphUsingUuids()
{
static::defaultMorphKeyType('uuid');
}






public static function morphUsingUlids()
{
static::defaultMorphKeyType('ulid');
}







public function createDatabase($name)
{
return $this->connection->statement(
$this->grammar->compileCreateDatabase($name)
);
}







public function dropDatabaseIfExists($name)
{
return $this->connection->statement(
$this->grammar->compileDropDatabaseIfExists($name)
);
}






public function getSchemas()
{
return $this->connection->getPostProcessor()->processSchemas(
$this->connection->selectFromWriteConnection($this->grammar->compileSchemas())
);
}







public function hasTable($table)
{
[$schema, $table] = $this->parseSchemaAndTable($table);

$table = $this->connection->getTablePrefix().$table;

if ($sql = $this->grammar->compileTableExists($schema, $table)) {
return (bool) $this->connection->scalar($sql);
}

foreach ($this->getTables($schema ?? $this->getCurrentSchemaName()) as $value) {
if (strtolower($table) === strtolower($value['name'])) {
return true;
}
}

return false;
}







public function hasView($view)
{
[$schema, $view] = $this->parseSchemaAndTable($view);

$view = $this->connection->getTablePrefix().$view;

foreach ($this->getViews($schema ?? $this->getCurrentSchemaName()) as $value) {
if (strtolower($view) === strtolower($value['name'])) {
return true;
}
}

return false;
}







public function getTables($schema = null)
{
return $this->connection->getPostProcessor()->processTables(
$this->connection->selectFromWriteConnection($this->grammar->compileTables($schema))
);
}








public function getTableListing($schema = null, $schemaQualified = true)
{
return array_column(
$this->getTables($schema),
$schemaQualified ? 'schema_qualified_name' : 'name'
);
}







public function getViews($schema = null)
{
return $this->connection->getPostProcessor()->processViews(
$this->connection->selectFromWriteConnection($this->grammar->compileViews($schema))
);
}







public function getTypes($schema = null)
{
return $this->connection->getPostProcessor()->processTypes(
$this->connection->selectFromWriteConnection($this->grammar->compileTypes($schema))
);
}








public function hasColumn($table, $column)
{
return in_array(
strtolower($column), array_map(strtolower(...), $this->getColumnListing($table))
);
}








public function hasColumns($table, array $columns)
{
$tableColumns = array_map(strtolower(...), $this->getColumnListing($table));

foreach ($columns as $column) {
if (! in_array(strtolower($column), $tableColumns)) {
return false;
}
}

return true;
}









public function whenTableHasColumn(string $table, string $column, Closure $callback)
{
if ($this->hasColumn($table, $column)) {
$this->table($table, fn (Blueprint $table) => $callback($table));
}
}









public function whenTableDoesntHaveColumn(string $table, string $column, Closure $callback)
{
if (! $this->hasColumn($table, $column)) {
$this->table($table, fn (Blueprint $table) => $callback($table));
}
}










public function whenTableHasIndex(string $table, string|array $index, Closure $callback, ?string $type = null)
{
if ($this->hasIndex($table, $index, $type)) {
$this->table($table, fn (Blueprint $table) => $callback($table));
}
}










public function whenTableDoesntHaveIndex(string $table, string|array $index, Closure $callback, ?string $type = null)
{
if (! $this->hasIndex($table, $index, $type)) {
$this->table($table, fn (Blueprint $table) => $callback($table));
}
}









public function getColumnType($table, $column, $fullDefinition = false)
{
$columns = $this->getColumns($table);

foreach ($columns as $value) {
if (strtolower($value['name']) === strtolower($column)) {
return $fullDefinition ? $value['type'] : $value['type_name'];
}
}

throw new InvalidArgumentException("There is no column with name '$column' on table '$table'.");
}







public function getColumnListing($table)
{
return array_column($this->getColumns($table), 'name');
}







public function getColumns($table)
{
[$schema, $table] = $this->parseSchemaAndTable($table);

$table = $this->connection->getTablePrefix().$table;

return $this->connection->getPostProcessor()->processColumns(
$this->connection->selectFromWriteConnection(
$this->grammar->compileColumns($schema, $table)
)
);
}







public function getIndexes($table)
{
[$schema, $table] = $this->parseSchemaAndTable($table);

$table = $this->connection->getTablePrefix().$table;

return $this->connection->getPostProcessor()->processIndexes(
$this->connection->selectFromWriteConnection(
$this->grammar->compileIndexes($schema, $table)
)
);
}







public function getIndexListing($table)
{
return array_column($this->getIndexes($table), 'name');
}









public function hasIndex($table, $index, $type = null)
{
$type = is_null($type) ? $type : strtolower($type);

foreach ($this->getIndexes($table) as $value) {
$typeMatches = is_null($type)
|| ($type === 'primary' && $value['primary'])
|| ($type === 'unique' && $value['unique'])
|| $type === $value['type'];

if (($value['name'] === $index || $value['columns'] === $index) && $typeMatches) {
return true;
}
}

return false;
}







public function getForeignKeys($table)
{
[$schema, $table] = $this->parseSchemaAndTable($table);

$table = $this->connection->getTablePrefix().$table;

return $this->connection->getPostProcessor()->processForeignKeys(
$this->connection->selectFromWriteConnection(
$this->grammar->compileForeignKeys($schema, $table)
)
);
}








public function table($table, Closure $callback)
{
$this->build($this->createBlueprint($table, $callback));
}








public function create($table, Closure $callback)
{
$this->build(tap($this->createBlueprint($table), function ($blueprint) use ($callback) {
$blueprint->create();

$callback($blueprint);
}));
}







public function drop($table)
{
$this->build(tap($this->createBlueprint($table), function ($blueprint) {
$blueprint->drop();
}));
}







public function dropIfExists($table)
{
$this->build(tap($this->createBlueprint($table), function ($blueprint) {
$blueprint->dropIfExists();
}));
}








public function dropColumns($table, $columns)
{
$this->table($table, function (Blueprint $blueprint) use ($columns) {
$blueprint->dropColumn($columns);
});
}








public function dropAllTables()
{
throw new LogicException('This database driver does not support dropping all tables.');
}








public function dropAllViews()
{
throw new LogicException('This database driver does not support dropping all views.');
}








public function dropAllTypes()
{
throw new LogicException('This database driver does not support dropping all types.');
}








public function rename($from, $to)
{
$this->build(tap($this->createBlueprint($from), function ($blueprint) use ($to) {
$blueprint->rename($to);
}));
}






public function enableForeignKeyConstraints()
{
return $this->connection->statement(
$this->grammar->compileEnableForeignKeyConstraints()
);
}






public function disableForeignKeyConstraints()
{
return $this->connection->statement(
$this->grammar->compileDisableForeignKeyConstraints()
);
}

/**
@template





*/
public function withoutForeignKeyConstraints(Closure $callback)
{
$this->disableForeignKeyConstraints();

try {
return $callback();
} finally {
$this->enableForeignKeyConstraints();
}
}







public function ensureVectorExtensionExists($schema = null)
{
$this->ensureExtensionExists('vector', $schema);
}








public function ensureExtensionExists($name, $schema = null)
{
if (! $this->getConnection() instanceof PostgresConnection) {
throw new RuntimeException('Extensions are only supported by Postgres.');
}

$name = $this->getConnection()->getSchemaGrammar()->wrap($name);

$this->getConnection()->statement(match (filled($schema)) {
true => "create extension if not exists {$name} schema {$this->getConnection()->getSchemaGrammar()->wrap($schema)}",
false => "create extension if not exists {$name}",
});
}







protected function build(Blueprint $blueprint)
{
$blueprint->build();
}








protected function createBlueprint($table, ?Closure $callback = null)
{
$connection = $this->connection;

if (isset($this->resolver)) {
return call_user_func($this->resolver, $connection, $table, $callback);
}

return Container::getInstance()->make(Blueprint::class, compact('connection', 'table', 'callback'));
}






public function getCurrentSchemaListing()
{
return null;
}






public function getCurrentSchemaName()
{
return $this->getCurrentSchemaListing()[0] ?? null;
}








public function parseSchemaAndTable($reference, $withDefaultSchema = null)
{
$segments = explode('.', $reference);

if (count($segments) > 2) {
throw new InvalidArgumentException(
"Using three-part references is not supported, you may use `Schema::connection('{$segments[0]}')` instead."
);
}

$table = $segments[1] ?? $segments[0];

$schema = match (true) {
isset($segments[1]) => $segments[0],
is_string($withDefaultSchema) => $withDefaultSchema,
$withDefaultSchema => $this->getCurrentSchemaName(),
default => null,
};

return [$schema, $table];
}






public function getConnection()
{
return $this->connection;
}







public function blueprintResolver(Closure $resolver)
{
$this->resolver = $resolver;
}
}
