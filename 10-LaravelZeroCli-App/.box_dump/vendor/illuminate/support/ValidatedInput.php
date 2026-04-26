<?php

namespace Illuminate\Support;

use ArrayIterator;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Support\Traits\Dumpable;
use Illuminate\Support\Traits\InteractsWithData;
use Traversable;

class ValidatedInput implements ValidatedData
{
use Dumpable, InteractsWithData;






protected $input;






public function __construct(array $input)
{
$this->input = $input;
}







public function merge(array $items)
{
return new static(array_merge($this->all(), $items));
}







public function all($keys = null)
{
if (! $keys) {
return $this->input;
}

$input = [];

foreach (is_array($keys) ? $keys : func_get_args() as $key) {
Arr::set($input, $key, Arr::get($this->input, $key));
}

return $input;
}








protected function data($key = null, $default = null)
{
return $this->input($key, $default);
}






public function keys()
{
return array_keys($this->input());
}








public function input($key = null, $default = null)
{
return data_get(
$this->all(), $key, $default
);
}







public function dump(...$keys)
{
dump(count($keys) > 0 ? $this->only($keys) : $this->all());

return $this;
}






public function toArray()
{
return $this->all();
}







public function __get($name)
{
return $this->input($name);
}








public function __set($name, $value)
{
$this->input[$name] = $value;
}







public function __isset($name)
{
return $this->exists($name);
}







public function __unset($name)
{
unset($this->input[$name]);
}







public function offsetExists($key): bool
{
return $this->exists($key);
}







public function offsetGet($key): mixed
{
return $this->input($key);
}








public function offsetSet($key, $value): void
{
if (is_null($key)) {
$this->input[] = $value;
} else {
$this->input[$key] = $value;
}
}







public function offsetUnset($key): void
{
unset($this->input[$key]);
}






public function getIterator(): Traversable
{
return new ArrayIterator($this->input);
}
}
