<?php

namespace Illuminate\Foundation\Exceptions\Renderer;

use Closure;
use Composer\Autoload\ClassLoader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

class Exception
{





protected $exception;






protected $request;






protected $listener;






protected $basePath;









public function __construct(FlattenException $exception, Request $request, Listener $listener, string $basePath)
{
$this->exception = $exception;
$this->request = $request;
$this->listener = $listener;
$this->basePath = $basePath;
}






public function title()
{
return $this->exception->getStatusText();
}






public function message()
{
return $this->exception->getMessage();
}






public function class()
{
return $this->exception->getClass();
}






public function code()
{
return $this->exception->getCode();
}






public function httpStatusCode()
{
return $this->exception->getStatusCode();
}






public function frames()
{
return once(function () {
$classMap = array_map(function ($path) {
return (string) realpath($path);
}, array_values(ClassLoader::getRegisteredLoaders())[0]->getClassMap());

$trace = $this->exception->getTrace();

if (count($trace) > 1 && empty($trace[0]['class']) && empty($trace[0]['function'])) {
$trace[0]['class'] = $trace[1]['class'] ?? '';
$trace[0]['type'] = $trace[1]['type'] ?? '';
$trace[0]['function'] = $trace[1]['function'] ?? '';
$trace[0]['args'] = $trace[1]['args'] ?? [];
}

$trace = array_values(array_filter(
$trace, fn ($trace) => isset($trace['file']),
));

if (($trace[1]['class'] ?? '') === HandleExceptions::class) {
array_shift($trace);
array_shift($trace);
}

$frames = [];
$previousFrame = null;

foreach (array_reverse($trace) as $frameData) {
$frame = new Frame($this->exception, $classMap, $frameData, $this->basePath, $previousFrame);
$frames[] = $frame;
$previousFrame = $frame;
}

$frames = array_reverse($frames);

foreach ($frames as $frame) {
if (! $frame->isFromVendor()) {
$frame->markAsMain();
break;
}
}

return new Collection($frames);
});
}






public function frameGroups()
{
$groups = [];

foreach ($this->frames() as $frame) {
$isVendor = $frame->isFromVendor();

if (empty($groups) || $groups[array_key_last($groups)]['is_vendor'] !== $isVendor) {
$groups[] = [
'is_vendor' => $isVendor,
'frames' => [],
];
}

$groups[array_key_last($groups)]['frames'][] = $frame;
}

return $groups;
}






public function request()
{
return $this->request;
}






public function requestHeaders()
{
return array_map(function (array $header) {
return implode(', ', $header);
}, $this->request()->headers->all());
}






public function requestBody()
{
if (empty($payload = $this->request()->all())) {
return null;
}

$json = (string) json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

return str_replace('\\', '', $json);
}






public function applicationRouteContext()
{
$route = $this->request()->route();

return $route ? array_filter([
'controller' => $route->getActionName(),
'route name' => $route->getName() ?: null,
'middleware' => implode(', ', array_map(function ($middleware) {
return $middleware instanceof Closure ? 'Closure' : $middleware;
}, $route->gatherMiddleware())),
]) : [];
}






public function applicationRouteParametersContext()
{
$parameters = $this->request()->route()?->parameters();

return $parameters ? json_encode(array_map(
fn ($value) => $value instanceof Model ? $value->withoutRelations() : $value,
$parameters
), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : null;
}






public function applicationQueries()
{
return array_map(function (array $query) {
$sql = $query['sql'];

foreach ($query['bindings'] as $binding) {
$sql = match (gettype($binding)) {
'integer', 'double' => preg_replace('/\?/', $binding, $sql, 1),
'NULL' => preg_replace('/\?/', 'NULL', $sql, 1),
default => preg_replace('/\?/', "'$binding'", $sql, 1),
};
}

return [
'connectionName' => $query['connectionName'],
'time' => $query['time'],
'sql' => $sql,
];
}, $this->listener->queries());
}
}
