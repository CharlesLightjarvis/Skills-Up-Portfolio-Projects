<?php

namespace Illuminate\Foundation\Http\Middleware;

use Illuminate\Container\Container;
use Illuminate\Foundation\Routing\PrecognitionCallableDispatcher;
use Illuminate\Foundation\Routing\PrecognitionControllerDispatcher;
use Illuminate\Routing\Contracts\CallableDispatcher as CallableDispatcherContract;
use Illuminate\Routing\Contracts\ControllerDispatcher as ControllerDispatcherContract;

class HandlePrecognitiveRequests
{





protected $container;






public function __construct(Container $container)
{
$this->container = $container;
}








public function handle($request, $next)
{
if (! $request->isAttemptingPrecognition()) {
return $this->appendVaryHeader($request, $next($request));
}

$bindings = $this->container->getBindings();
$callableBinding = $bindings[CallableDispatcherContract::class] ?? null;
$controllerBinding = $bindings[ControllerDispatcherContract::class] ?? null;

$this->prepareForPrecognition($request);

return tap($next($request), function ($response) use ($request, $callableBinding, $controllerBinding) {
$response->headers->set('Precognition', 'true');

$this->appendVaryHeader($request, $response);

$this->restoreDispatchers($callableBinding, $controllerBinding);
});
}







protected function prepareForPrecognition($request)
{
$request->attributes->set('precognitive', true);

$this->container->bind(CallableDispatcherContract::class, fn ($app) => new PrecognitionCallableDispatcher($app));
$this->container->bind(ControllerDispatcherContract::class, fn ($app) => new PrecognitionControllerDispatcher($app));
}








protected function appendVaryHeader($request, $response)
{
return tap($response, fn () => $response->headers->set('Vary', implode(', ', array_filter([
$response->headers->get('Vary'),
'Precognition',
]))));
}








protected function restoreDispatchers($callableBinding, $controllerBinding)
{
if ($callableBinding) {
$this->container->bind(CallableDispatcherContract::class, $callableBinding['concrete'], $callableBinding['shared']);
}

if ($controllerBinding) {
$this->container->bind(ControllerDispatcherContract::class, $controllerBinding['concrete'], $controllerBinding['shared']);
}
}
}
