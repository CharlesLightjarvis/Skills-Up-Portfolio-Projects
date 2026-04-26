<?php

namespace Illuminate\Database;

use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\Events\ConnectionEstablished;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\ConfigurationUrlParser;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use PDO;
use RuntimeException;

use function Illuminate\Support\enum_value;

/**
@mixin
*/
class DatabaseManager implements ConnectionResolverInterface
{
use Macroable {
__call as macroCall;
}






protected $app;






protected $factory;






protected $connections = [];






protected $dynamicConnectionConfigurations = [];






protected $extensions = [];






protected $reconnector;







public function __construct($app, ConnectionFactory $factory)
{
$this->app = $app;
$this->factory = $factory;

$this->reconnector = function ($connection) {
$connection->setPdo(
$this->reconnect($connection->getNameWithReadWriteType())->getRawPdo()
);
};
}







public function connection($name = null)
{
[$database, $type] = $this->parseConnectionName($name = enum_value($name) ?: $this->getDefaultConnection());




if (! isset($this->connections[$name])) {
$this->connections[$name] = $this->configure(
$this->makeConnection($database), $type
);

$this->dispatchConnectionEstablishedEvent($this->connections[$name]);
}

return $this->connections[$name];
}







public function build(array $config)
{
$config['name'] ??= static::calculateDynamicConnectionName($config);

$this->dynamicConnectionConfigurations[$config['name']] = $config;

return $this->connectUsing($config['name'], $config, true);
}







public static function calculateDynamicConnectionName(array $config)
{
return 'dynamic_'.md5((new Collection($config))->map(function ($value, $key) {
return $key.(is_string($value) || is_int($value) ? $value : '');
})->implode(''));
}









public function connectUsing(string $name, array $config, bool $force = false)
{
if ($force) {
$this->purge($name = enum_value($name));
}

if (isset($this->connections[$name])) {
throw new RuntimeException("Cannot establish connection [$name] because another connection with that name already exists.");
}

$connection = $this->configure(
$this->factory->make($config, $name), null
);

$this->dispatchConnectionEstablishedEvent($connection);

return tap($connection, fn ($connection) => $this->connections[$name] = $connection);
}







protected function parseConnectionName($name)
{
return Str::endsWith($name, ['::read', '::write'])
? explode('::', $name, 2)
: [$name, null];
}







protected function makeConnection($name)
{
$config = $this->configuration($name);




if (isset($this->extensions[$name])) {
return call_user_func($this->extensions[$name], $config, $name);
}




if (isset($this->extensions[$driver = $config['driver']])) {
return call_user_func($this->extensions[$driver], $config, $name);
}

return $this->factory->make($config, $name);
}









protected function configuration($name)
{
$connections = $this->app['config']['database.connections'];

$config = $this->dynamicConnectionConfigurations[$name] ?? Arr::get($connections, $name);

if (is_null($config)) {
throw new InvalidArgumentException("Database connection [{$name}] not configured.");
}

return (new ConfigurationUrlParser)
->parseConfiguration($config);
}








protected function configure(Connection $connection, $type)
{
$connection = $this->setPdoForType($connection, $type)->setReadWriteType($type);




if ($this->app->bound('events')) {
$connection->setEventDispatcher($this->app['events']);
}

if ($this->app->bound('db.transactions')) {
$connection->setTransactionManager($this->app['db.transactions']);
}




$connection->setReconnector($this->reconnector);

return $connection;
}







protected function dispatchConnectionEstablishedEvent(Connection $connection)
{
if (! $this->app->bound('events')) {
return;
}

$this->app['events']->dispatch(
new ConnectionEstablished($connection)
);
}








protected function setPdoForType(Connection $connection, $type = null)
{
if ($type === 'read') {
$connection->setPdo($connection->getReadPdo());
} elseif ($type === 'write') {
$connection->setReadPdo($connection->getPdo());
}

return $connection;
}







public function purge($name = null)
{
$this->disconnect($name = enum_value($name) ?: $this->getDefaultConnection());

unset($this->connections[$name]);
}







public function disconnect($name = null)
{
if (isset($this->connections[$name = enum_value($name) ?: $this->getDefaultConnection()])) {
$this->connections[$name]->disconnect();
}
}







public function reconnect($name = null)
{
$this->disconnect($name = enum_value($name) ?: $this->getDefaultConnection());

if (! isset($this->connections[$name])) {
return $this->connection($name);
}

return tap($this->refreshPdoConnections($name), function ($connection) {
$this->dispatchConnectionEstablishedEvent($connection);
});
}








public function usingConnection($name, callable $callback)
{
$previousName = $this->getDefaultConnection();

$this->setDefaultConnection($name = enum_value($name));

try {
return $callback();
} finally {
$this->setDefaultConnection($previousName);
}
}







protected function refreshPdoConnections($name)
{
[$database, $type] = $this->parseConnectionName($name);

$fresh = $this->configure(
$this->makeConnection($database), $type
);

return $this->connections[$name]
->setPdo($fresh->getRawPdo())
->setReadPdo($fresh->getRawReadPdo());
}






public function getDefaultConnection()
{
return $this->app['config']['database.default'];
}







public function setDefaultConnection($name)
{
$this->app['config']['database.default'] = $name;
}






public function supportedDrivers()
{
return ['mysql', 'mariadb', 'pgsql', 'sqlite', 'sqlsrv'];
}






public function availableDrivers()
{
return array_intersect(
$this->supportedDrivers(),
str_replace('dblib', 'sqlsrv', PDO::getAvailableDrivers())
);
}








public function extend($name, callable $resolver)
{
$this->extensions[$name] = $resolver;
}







public function forgetExtension($name)
{
unset($this->extensions[$name]);
}






public function getConnections()
{
return $this->connections;
}







public function setReconnector(callable $reconnector)
{
$this->reconnector = $reconnector;
}







public function setApplication($app)
{
$this->app = $app;

return $this;
}








public function __call($method, $parameters)
{
if (static::hasMacro($method)) {
return $this->macroCall($method, $parameters);
}

return $this->connection()->$method(...$parameters);
}
}
