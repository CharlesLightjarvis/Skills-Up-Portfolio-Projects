<?php

namespace Illuminate\Database\Eloquent\Concerns;

use Illuminate\Support\Str;

trait HasUlids
{
use HasUniqueStringIds;






public function newUniqueId()
{
return strtolower((string) Str::ulid());
}







protected function isValidUniqueId($value): bool
{
return Str::isUlid($value);
}
}
