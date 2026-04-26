<?php

namespace Illuminate\Foundation\Testing;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;

trait WithCachedConfig
{



protected function setUpWithCachedConfig(): void
{
if ((CachedState::$cachedConfig ?? null) === null) {
CachedState::$cachedConfig = $this->app->make('config')->all();
}

$this->markConfigCached($this->app);
}






protected function tearDownWithCachedConfig(): void
{
LoadConfiguration::alwaysUse(null);
}




protected function markConfigCached(Application $app): void
{
$app->instance('config_loaded_from_cache', true);

LoadConfiguration::alwaysUse(static fn () => CachedState::$cachedConfig);
}
}
