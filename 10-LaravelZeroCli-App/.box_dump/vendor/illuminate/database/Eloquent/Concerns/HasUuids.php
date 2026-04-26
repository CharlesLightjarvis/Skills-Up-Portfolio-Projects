<?php

namespace Illuminate\Database\Eloquent\Concerns;

use Illuminate\Support\Str;

trait HasUuids
{
use HasUniqueStringIds;






public function newUniqueId()
{
return (string) Str::uuid7();
}







protected function isValidUniqueId($value): bool
{
return Str::isUuid($value);
}
}
