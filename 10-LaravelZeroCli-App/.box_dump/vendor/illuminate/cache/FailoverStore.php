<?php

namespace Illuminate\Cache;

use Illuminate\Cache\Events\CacheFailedOver;
use Illuminate\Contracts\Cache\LockProvider;
use Illuminate\Contracts\Events\Dispatcher;
use RuntimeException;
use Throwable;

class FailoverStore extends TaggableStore implements LockProvider
{





protected array $failingCaches = [];






public function __construct(
protected CacheManager $cache,
protected Dispatcher $events,
protected array $stores
) {
}







public function get($key)
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}








public function many(array $keys)
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}









public function put($key, $value, $seconds)
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}







public function putMany(array $values, $seconds)
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}









public function add($key, $value, $seconds)
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}








public function increment($key, $value = 1)
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}








public function decrement($key, $value = 1)
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}








public function forever($key, $value)
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}









public function lock($name, $seconds = 0, $owner = null)
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}








public function restoreLock($name, $owner)
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}







public function forget($key)
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}






public function flush()
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}






public function flushStaleTags()
{
foreach ($this->stores as $store) {
if ($this->store($store)->getStore() instanceof RedisStore) {
$this->store($store)->flushStaleTags();

break;
}
}
}






public function getPrefix()
{
return $this->attemptOnAllStores(__FUNCTION__, func_get_args());
}








protected function attemptOnAllStores(string $method, array $arguments)
{
[$lastException, $failedCaches] = [null, []];

try {
foreach ($this->stores as $store) {
try {
return $this->store($store)->{$method}(...$arguments);
} catch (Throwable $e) {
$lastException = $e;

$failedCaches[] = $store;

if (! in_array($store, $this->failingCaches)) {
$this->events->dispatch(new CacheFailedOver($store, $e));
}
}
}
} finally {
$this->failingCaches = $failedCaches;
}

throw $lastException ?? new RuntimeException('All failover cache stores failed.');
}






protected function store(string $store)
{
return $this->cache->store($store);
}
}
