<?php

namespace Illuminate\Foundation\Events;

trait Dispatchable
{






public static function dispatch(...$arguments)
{
return event(new static(...$arguments));
}








public static function dispatchIf($boolean, ...$arguments)
{
if ($boolean) {
return event(new static(...$arguments));
}
}








public static function dispatchUnless($boolean, ...$arguments)
{
if (! $boolean) {
return event(new static(...$arguments));
}
}







public static function broadcast(...$arguments)
{
return broadcast(new static(...$arguments));
}
}
