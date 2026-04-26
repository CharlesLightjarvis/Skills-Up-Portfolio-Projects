<?php

namespace Illuminate\Support\Facades;

use Mockery;


































































class Cache extends Facade
{





protected static function getFacadeAccessor()
{
return 'cache';
}






public static function spy()
{
if (! static::isMock()) {
$class = static::getMockableClass();
$instance = static::getFacadeRoot();

if ($class && $instance) {
return tap(Mockery::spy($instance)->makePartial(), function ($spy) {
static::swap($spy);
});
}

return tap($class ? Mockery::spy($class) : Mockery::spy(), function ($spy) {
static::swap($spy);
});
}
}
}
