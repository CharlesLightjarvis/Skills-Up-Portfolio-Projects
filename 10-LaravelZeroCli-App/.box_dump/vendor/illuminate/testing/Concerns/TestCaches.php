<?php

namespace Illuminate\Testing\Concerns;

use Illuminate\Support\Facades\ParallelTesting;

trait TestCaches
{





protected static $originalCachePrefix = null;






protected function bootTestCache()
{
ParallelTesting::setUpTestCase(function () {
if (ParallelTesting::option('without_cache')) {
return;
}

$this->switchToCachePrefix($this->parallelSafeCachePrefix());
});
}






protected function parallelSafeCachePrefix()
{
self::$originalCachePrefix ??= $this->app['config']->get('cache.prefix', '');

return self::$originalCachePrefix.'test_'.ParallelTesting::token().'_';
}







protected function switchToCachePrefix($prefix)
{
$this->app['config']->set('cache.prefix', $prefix);
}
}
