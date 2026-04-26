<?php

namespace Illuminate\Cache;

use Aws\DynamoDb\DynamoDbClient;
use Closure;
use Illuminate\Contracts\Cache\Factory as FactoryContract;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Mockery;
use Mockery\LegacyMockInterface;

/**
@mixin
@mixin
*/
class CacheManager implements FactoryContract
{





protected $app;






protected $stores = [];






protected $customCreators = [];






public function __construct($app)
{
$this->app = $app;
}







public function store($name = null)
{
$name = $name ?? $this->getDefaultDriver();

return $this->stores[$name] ??= $this->resolve($name);
}







public function driver($driver = null)
{
return $this->store($driver);
}







public function memo($driver = null)
{
$driver = $driver ?? $this->getDefaultDriver();

$bindingKey = "cache.__memoized:{$driver}";

$isSpy = isset($this->app['cache']) && $this->app['cache'] instanceof LegacyMockInterface;

$this->app->scopedIf($bindingKey, function () use ($driver, $isSpy) {
$repository = $this->repository(
new MemoizedStore($driver, $this->store($driver)), ['events' => false]
);

return $isSpy ? Mockery::spy($repository) : $repository;
});

return $this->app->make($bindingKey);
}









public function resolve($name)
{
$config = $this->getConfig($name);

if (is_null($config)) {
throw new InvalidArgumentException("Cache store [{$name}] is not defined.");
}

$config = Arr::add($config, 'store', $name);

return $this->build($config);
}









public function build(array $config)
{
$config = Arr::add($config, 'store', $config['name'] ?? 'ondemand');

if (isset($this->customCreators[$config['driver']])) {
return $this->callCustomCreator($config);
}

$driverMethod = 'create'.ucfirst($config['driver']).'Driver';

if (method_exists($this, $driverMethod)) {
return $this->{$driverMethod}($config);
}

throw new InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
}







protected function callCustomCreator(array $config)
{
return $this->customCreators[$config['driver']]($this->app, $config);
}







protected function createApcDriver(array $config)
{
$prefix = $this->getPrefix($config);

return $this->repository(new ApcStore(new ApcWrapper, $prefix), $config);
}







protected function createArrayDriver(array $config)
{
return $this->repository(new ArrayStore(
$config['serialize'] ?? false,
$this->getSerializableClasses($config),
), $config);
}







protected function createDatabaseDriver(array $config)
{
$connection = $this->app['db']->connection($config['connection'] ?? null);

$store = new DatabaseStore(
$connection,
$config['table'],
$this->getPrefix($config),
$config['lock_table'] ?? 'cache_locks',
$config['lock_lottery'] ?? [2, 100],
$config['lock_timeout'] ?? 86400,
$this->getSerializableClasses($config),
);

return $this->repository(
$store->setLockConnection(
$this->app['db']->connection($config['lock_connection'] ?? $config['connection'] ?? null)
),
$config
);
}







protected function createDynamodbDriver(array $config)
{
$client = $this->newDynamodbClient($config);

return $this->repository(
new DynamoDbStore(
$client,
$config['table'],
$config['attributes']['key'] ?? 'key',
$config['attributes']['value'] ?? 'value',
$config['attributes']['expiration'] ?? 'expires_at',
$this->getPrefix($config),
$this->getSerializableClasses($config),
),
$config
);
}






protected function newDynamodbClient(array $config)
{
$dynamoConfig = [
'region' => $config['region'],
'version' => 'latest',
'endpoint' => $config['endpoint'] ?? null,
];

if (! empty($config['key']) && ! empty($config['secret'])) {
$dynamoConfig['credentials'] = Arr::only(
$config, ['key', 'secret']
);

if (! empty($config['token'])) {
$dynamoConfig['credentials']['token'] = $config['token'];
}
}

return new DynamoDbClient($dynamoConfig);
}







protected function createFailoverDriver(array $config)
{
return $this->repository(new FailoverStore(
$this,
$this->app->make(DispatcherContract::class),
$config['stores']
), ['events' => false, ...$config]);
}







protected function createFileDriver(array $config)
{
return $this->repository(
(new FileStore(
$this->app['files'],
$config['path'],
$config['permission'] ?? null,
$this->getSerializableClasses($config),
))
->setLockDirectory($config['lock_path'] ?? null),
$config
);
}







protected function createMemcachedDriver(array $config)
{
$prefix = $this->getPrefix($config);

$memcached = $this->app['memcached.connector']->connect(
$config['servers'],
$config['persistent_id'] ?? null,
$config['options'] ?? [],
array_filter($config['sasl'] ?? [])
);

return $this->repository(new MemcachedStore($memcached, $prefix), $config);
}






protected function createNullDriver()
{
return $this->repository(new NullStore, []);
}







protected function createRedisDriver(array $config)
{
$redis = $this->app['redis'];

$connection = $config['connection'] ?? 'default';

$store = new RedisStore(
$redis,
$this->getPrefix($config),
$connection,
$this->getSerializableClasses($config),
);

return $this->repository(
$store->setLockConnection($config['lock_connection'] ?? $connection),
$config
);
}







protected function createSessionDriver(array $config)
{
return $this->repository(
new SessionStore(
$this->getSession(),
$config['key'] ?? '_cache',
),
$config
);
}








protected function getSession()
{
$session = $this->app['session'] ?? null;

if (! $session) {
throw new InvalidArgumentException('Session store requires session manager to be available in container.');
}

return $session;
}








public function repository(Store $store, array $config = [])
{
return tap(new Repository($store, Arr::only($config, ['store'])), function ($repository) use ($config) {
if ($config['events'] ?? true) {
$this->setEventDispatcher($repository);
}
});
}







protected function setEventDispatcher(Repository $repository)
{
if (! $this->app->bound(DispatcherContract::class)) {
return;
}

$repository->setEventDispatcher(
$this->app[DispatcherContract::class]
);
}






public function refreshEventDispatcher()
{
array_map($this->setEventDispatcher(...), $this->stores);
}







protected function getPrefix(array $config)
{
return $config['prefix'] ?? $this->app['config']['cache.prefix'];
}







protected function getSerializableClasses(array $config)
{
return $this->app['config']['cache.serializable_classes'] ?? null;
}







protected function getConfig($name)
{
return $name !== 'null'
? $this->app['config']["cache.stores.{$name}"]
: ['driver' => 'null'];
}






public function getDefaultDriver()
{
return $this->app['config']['cache.default'] ?? 'null';
}







public function setDefaultDriver($name)
{
$this->app['config']['cache.default'] = $name;
}







public function forgetDriver($name = null)
{
$name ??= $this->getDefaultDriver();

foreach ((array) $name as $cacheName) {
if (isset($this->stores[$cacheName])) {
unset($this->stores[$cacheName]);
}
}

return $this;
}







public function purge($name = null)
{
$name ??= $this->getDefaultDriver();

unset($this->stores[$name]);
}

/**
@param-closure-this







*/
public function extend($driver, Closure $callback)
{
$this->customCreators[$driver] = $callback->bindTo($this, $this);

return $this;
}







public function setApplication($app)
{
$this->app = $app;

return $this;
}








public function __call($method, $parameters)
{
return $this->store()->$method(...$parameters);
}
}
