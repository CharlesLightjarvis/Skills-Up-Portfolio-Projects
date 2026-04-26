<?php

namespace Illuminate\Database;

use Illuminate\Container\Container;
use Illuminate\Contracts\Database\LostConnectionDetector as LostConnectionDetectorContract;
use Throwable;

trait DetectsLostConnections
{






protected function causedByLostConnection(Throwable $e)
{
$container = Container::getInstance();

$detector = $container->bound(LostConnectionDetectorContract::class)
? $container[LostConnectionDetectorContract::class]
: new LostConnectionDetector();

return $detector->causedByLostConnection($e);
}
}
