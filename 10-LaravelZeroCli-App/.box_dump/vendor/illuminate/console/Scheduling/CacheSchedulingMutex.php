<?php

namespace Illuminate\Console\Scheduling;

use DateTimeInterface;
use Illuminate\Cache\DynamoDbStore;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Cache\LockProvider;

class CacheSchedulingMutex implements SchedulingMutex, CacheAware
{





public $cache;






public $store;






public function __construct(Cache $cache)
{
$this->cache = $cache;
}








public function create(Event $event, DateTimeInterface $time)
{
$mutexName = $event->mutexName().$time->format('Hi');

if ($this->shouldUseLocks($this->cache->store($this->store)->getStore())) {
return $this->cache->store($this->store)->getStore()
->lock($mutexName, 3600)
->acquire();
}

return $this->cache->store($this->store)->add(
$mutexName, true, 3600
);
}








public function exists(Event $event, DateTimeInterface $time)
{
$mutexName = $event->mutexName().$time->format('Hi');

if ($this->shouldUseLocks($this->cache->store($this->store)->getStore())) {
return ! $this->cache->store($this->store)->getStore()
->lock($mutexName, 3600)
->get(fn () => true);
}

return $this->cache->store($this->store)->has($mutexName);
}







protected function shouldUseLocks($store)
{
return $store instanceof LockProvider && ! $store instanceof DynamoDbStore;
}







public function useStore($store)
{
$this->store = $store;

return $this;
}
}
