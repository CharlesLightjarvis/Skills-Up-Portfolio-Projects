<?php

namespace Illuminate\Database\Eloquent\Concerns;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait HasUniqueStringIds
{





abstract public function newUniqueId();







abstract protected function isValidUniqueId($value): bool;






public function initializeHasUniqueStringIds()
{
$this->usesUniqueIds = true;
}






public function uniqueIds()
{
return $this->usesUniqueIds() ? [$this->getKeyName()] : parent::uniqueIds();
}











public function resolveRouteBindingQuery($query, $value, $field = null)
{
if ($field && in_array($field, $this->uniqueIds()) && ! $this->isValidUniqueId($value)) {
$this->handleInvalidUniqueId($value, $field);
}

if (! $field && in_array($this->getRouteKeyName(), $this->uniqueIds()) && ! $this->isValidUniqueId($value)) {
$this->handleInvalidUniqueId($value, $field);
}

return parent::resolveRouteBindingQuery($query, $value, $field);
}






public function getKeyType()
{
if (in_array($this->getKeyName(), $this->uniqueIds())) {
return 'string';
}

return parent::getKeyType();
}






public function getIncrementing()
{
if (in_array($this->getKeyName(), $this->uniqueIds())) {
return false;
}

return parent::getIncrementing();
}










protected function handleInvalidUniqueId($value, $field)
{
throw (new ModelNotFoundException)->setModel(get_class($this), $value);
}
}
