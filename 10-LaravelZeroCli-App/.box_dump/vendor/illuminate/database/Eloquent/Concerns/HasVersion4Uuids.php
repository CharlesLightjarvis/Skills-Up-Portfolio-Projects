<?php

namespace Illuminate\Database\Eloquent\Concerns;

use Illuminate\Support\Str;

trait HasVersion4Uuids
{
use HasUuids;






public function newUniqueId()
{
return (string) Str::orderedUuid();
}
}
