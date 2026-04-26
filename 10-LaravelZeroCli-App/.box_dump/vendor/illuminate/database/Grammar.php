<?php

namespace Illuminate\Database;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use RuntimeException;

abstract class Grammar
{
use Macroable;






protected $connection;






public function __construct(Connection $connection)
{
$this->connection = $connection;
}







public function wrapArray(array $values)
{
return array_map($this->wrap(...), $values);
}








public function wrapTable($table, $prefix = null)
{
if ($this->isExpression($table)) {
return $this->getValue($table);
}

$prefix ??= $this->connection->getTablePrefix();




if (stripos($table, ' as ') !== false) {
return $this->wrapAliasedTable($table, $prefix);
}




if (str_contains($table, '.')) {
$table = substr_replace($table, '.'.$prefix, strrpos($table, '.'), 1);

return (new Collection(explode('.', $table)))
->map($this->wrapValue(...))
->implode('.');
}

return $this->wrapValue($prefix.$table);
}







public function wrap($value)
{
if ($this->isExpression($value)) {
return $this->getValue($value);
}




if (stripos($value, ' as ') !== false) {
return $this->wrapAliasedValue($value);
}




if ($this->isJsonSelector($value)) {
return $this->wrapJsonSelector($value);
}

return $this->wrapSegments(explode('.', $value));
}







protected function wrapAliasedValue($value)
{
$segments = preg_split('/\s+as\s+/i', $value);

return $this->wrap($segments[0]).' as '.$this->wrapValue($segments[1]);
}








protected function wrapAliasedTable($value, $prefix = null)
{
$segments = preg_split('/\s+as\s+/i', $value);

$prefix ??= $this->connection->getTablePrefix();

return $this->wrapTable($segments[0], $prefix).' as '.$this->wrapValue($prefix.$segments[1]);
}







protected function wrapSegments($segments)
{
return (new Collection($segments))->map(function ($segment, $key) use ($segments) {
return $key == 0 && count($segments) > 1
? $this->wrapTable($segment)
: $this->wrapValue($segment);
})->implode('.');
}







protected function wrapValue($value)
{
if ($value !== '*') {
return '"'.str_replace('"', '""', $value).'"';
}

return $value;
}









protected function wrapJsonSelector($value)
{
throw new RuntimeException('This database engine does not support JSON operations.');
}







protected function isJsonSelector($value)
{
return str_contains($value, '->');
}







public function columnize(array $columns)
{
return implode(', ', array_map($this->wrap(...), $columns));
}







public function parameterize(array $values)
{
return implode(', ', array_map($this->parameter(...), $values));
}







public function parameter($value)
{
return $this->isExpression($value) ? $this->getValue($value) : '?';
}







public function quoteString($value)
{
if (is_array($value)) {
return implode(', ', array_map([$this, __FUNCTION__], $value));
}

return "'$value'";
}








public function escape($value, $binary = false)
{
return $this->connection->escape($value, $binary);
}







public function isExpression($value)
{
return $value instanceof Expression;
}







public function getValue($expression)
{
if ($this->isExpression($expression)) {
return $this->getValue($expression->getValue($this));
}

return $expression;
}






public function getDateFormat()
{
return 'Y-m-d H:i:s';
}








public function getTablePrefix()
{
return $this->connection->getTablePrefix();
}









public function setTablePrefix($prefix)
{
$this->connection->setTablePrefix($prefix);

return $this;
}
}
