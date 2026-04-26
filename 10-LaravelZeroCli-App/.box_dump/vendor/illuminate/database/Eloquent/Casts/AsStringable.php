<?php

namespace Illuminate\Database\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Stringable;

class AsStringable implements Castable
{






public static function castUsing(array $arguments)
{
return new class implements CastsAttributes
{
public function get($model, $key, $value, $attributes)
{
return isset($value) ? new Stringable($value) : null;
}

public function set($model, $key, $value, $attributes)
{
return isset($value) ? (string) $value : null;
}
};
}
}
