<?php

namespace Illuminate\Testing\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\ParallelTesting;

trait TestViews
{





protected static $originalCompiledViewPath = null;






protected function bootTestViews()
{
ParallelTesting::setUpProcess(function () {
if ($path = $this->parallelSafeCompiledViewPath()) {
File::ensureDirectoryExists($path);
}
});

ParallelTesting::setUpTestCase(function () {
if ($path = $this->parallelSafeCompiledViewPath()) {
$this->switchToCompiledViewPath($path);
}
});

ParallelTesting::tearDownProcess(function () {
if ($path = $this->parallelSafeCompiledViewPath()) {
File::deleteDirectory($path);
}
});
}






protected function parallelSafeCompiledViewPath()
{
self::$originalCompiledViewPath ??= $this->app['config']->get('view.compiled', '');

if (! self::$originalCompiledViewPath) {
return null;
}

return rtrim(self::$originalCompiledViewPath, '\/')
.'/test_'
.ParallelTesting::token();
}







protected function switchToCompiledViewPath($path)
{
$this->app['config']->set('view.compiled', $path);

if ($this->app->resolved('blade.compiler')) {
$compiler = $this->app['blade.compiler'];

(function () use ($path) {
$this->cachePath = $path;
})->bindTo($compiler, $compiler)();
}
}
}
