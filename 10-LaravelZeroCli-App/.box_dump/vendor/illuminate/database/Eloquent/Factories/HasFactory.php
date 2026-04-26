<?php

namespace Illuminate\Database\Eloquent\Factories;

use Illuminate\Database\Eloquent\Attributes\UseFactory;

/**
@template
*/
trait HasFactory
{







public static function factory($count = null, $state = [])
{
$factory = static::newFactory() ?? Factory::factoryForModel(static::class);

return $factory
->count(is_numeric($count) ? $count : null)
->state(is_callable($count) || is_array($count) ? $count : $state);
}






protected static function newFactory()
{
if (isset(static::$factory)) {
return static::$factory::new();
}

return static::getUseFactoryAttribute() ?? null;
}






protected static function getUseFactoryAttribute()
{
$attributes = (new \ReflectionClass(static::class))
->getAttributes(UseFactory::class);

if ($attributes !== []) {
$useFactory = $attributes[0]->newInstance();

$factory = $useFactory->factoryClass::new();

$factory->guessModelNamesUsing(fn () => static::class);

return $factory;
}
}
}
