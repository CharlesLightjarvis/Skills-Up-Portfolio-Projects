<?php

namespace Illuminate\Database\Eloquent\Factories;

use Countable;

class Sequence implements Countable
{





protected $sequence;






public $count;






public $index = 0;






public function __construct(...$sequence)
{
$this->sequence = $sequence;
$this->count = count($sequence);
}






public function count(): int
{
return $this->count;
}








public function __invoke($attributes = [], $parent = null)
{
return tap(value($this->sequence[$this->index % $this->count], $this, $attributes, $parent), function () {
$this->index = $this->index + 1;
});
}
}
