<?php

namespace Illuminate\Foundation\Testing;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
use Concerns\InteractsWithContainer,
Concerns\MakesHttpRequests,
Concerns\InteractsWithAuthentication,
Concerns\InteractsWithConsole,
Concerns\InteractsWithDatabase,
Concerns\InteractsWithDeprecationHandling,
Concerns\InteractsWithExceptionHandling,
Concerns\InteractsWithSession,
Concerns\InteractsWithTime,
Concerns\InteractsWithTestCaseLifecycle,
Concerns\InteractsWithViews;






protected array $traitsUsedByTest;






public function createApplication()
{
$app = require Application::inferBasePath().'/bootstrap/app.php';

$this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));

if (isset(CachedState::$cachedConfig) &&
isset($this->traitsUsedByTest[WithCachedConfig::class])) {
$this->markConfigCached($app);
}

if (isset(CachedState::$cachedRoutes) &&
isset($this->traitsUsedByTest[WithCachedRoutes::class])) {
$app->booting(fn () => $this->markRoutesCached($app));
}

$app->make(Kernel::class)->bootstrap();

return $app;
}






protected function setUp(): void
{
$this->setUpTheTestEnvironment();
}






protected function refreshApplication()
{
$this->app = $this->createApplication();
}








protected function tearDown(): void
{
$this->tearDownTheTestEnvironment();
}






public static function tearDownAfterClass(): void
{
static::tearDownAfterClassUsingTestCase();
}
}
