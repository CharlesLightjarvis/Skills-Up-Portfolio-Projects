<?php

namespace Illuminate\Database\Schema;

use Illuminate\Support\Stringable;

class ForeignIdColumnDefinition extends ColumnDefinition
{





protected $blueprint;







public function __construct(Blueprint $blueprint, $attributes = [])
{
parent::__construct($attributes);

$this->blueprint = $blueprint;
}









public function constrained($table = null, $column = null, $indexName = null)
{
$table ??= $this->table;
$column ??= $this->referencesModelColumn ?? 'id';

return $this->references($column, $indexName)->on($table ?? (new Stringable($this->name))->beforeLast('_'.$column)->plural());
}








public function references($column, $indexName = null)
{
return $this->blueprint->foreign($this->name, $indexName)->references($column);
}
}
