<?php

namespace Illuminate\Foundation\Testing;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

trait WithCachedRoutes
{





protected function setUpWithCachedRoutes(): void
{
if ((CachedState::$cachedRoutes ?? null) === null) {
$routes = $this->app['router']->getRoutes();

$routes->refreshNameLookups();
$routes->refreshActionLookups();

CachedState::$cachedRoutes = $routes->compile();
}

$this->markRoutesCached($this->app);
}








protected function tearDownWithCachedRoutes(): void
{
RouteServiceProvider::loadCachedRoutesUsing(null);
}




protected function markRoutesCached(Application $app): void
{
$app->instance('routes.cached', true);

RouteServiceProvider::loadCachedRoutesUsing(
static fn () => app('router')->setCompiledRoutes(CachedState::$cachedRoutes)
);
}
}
