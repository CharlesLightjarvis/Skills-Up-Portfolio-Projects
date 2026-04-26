<?php

namespace Illuminate\Container;

use ArrayAccess;
use Closure;
use Exception;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Contracts\Container\ContextualAttribute;
use Illuminate\Contracts\Container\SelfBuilding;
use Illuminate\Support\Traits\ReflectsClosures;
use LogicException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionParameter;
use TypeError;

class Container implements ArrayAccess, ContainerContract
{
use ReflectsClosures;






protected static $instance;






protected $resolved = [];






protected $bindings = [];






protected $methodBindings = [];






protected $instances = [];






protected $scopedInstances = [];






protected $aliases = [];






protected $abstractAliases = [];






protected $extenders = [];






protected $tags = [];






protected $buildStack = [];






protected $with = [];






public $contextual = [];






public $contextualAttributes = [];






protected $checkedForAttributeBindings = [];






protected $checkedForSingletonOrScopedAttributes = [];






protected $reboundCallbacks = [];






protected $globalBeforeResolvingCallbacks = [];






protected $globalResolvingCallbacks = [];






protected $globalAfterResolvingCallbacks = [];






protected $beforeResolvingCallbacks = [];






protected $resolvingCallbacks = [];






protected $afterResolvingCallbacks = [];






protected $afterResolvingAttributeCallbacks = [];






protected $environmentResolver = null;







public function when($concrete)
{
$aliases = [];

foreach (Util::arrayWrap($concrete) as $c) {
$aliases[] = $this->getAlias($c);
}

return new ContextualBindingBuilder($this, $aliases);
}






public function whenHasAttribute(string $attribute, Closure $handler)
{
$this->contextualAttributes[$attribute] = $handler;
}







public function bound($abstract)
{
return isset($this->bindings[$abstract]) ||
isset($this->instances[$abstract]) ||
$this->isAlias($abstract);
}




public function has(string $id): bool
{
return $this->bound($id);
}







public function resolved($abstract)
{
if ($this->isAlias($abstract)) {
$abstract = $this->getAlias($abstract);
}

return isset($this->resolved[$abstract]) ||
isset($this->instances[$abstract]);
}







public function isShared($abstract)
{
if (isset($this->instances[$abstract])) {
return true;
}

if (isset($this->bindings[$abstract]['shared']) && $this->bindings[$abstract]['shared'] === true) {
return true;
}

if (! class_exists($abstract)) {
return false;
}

if (($scopedType = $this->getScopedTyped($abstract)) === null) {
return false;
}

if ($scopedType === 'scoped') {
if (! in_array($abstract, $this->scopedInstances, true)) {
$this->scopedInstances[] = $abstract;
}
}

return true;
}







protected function getScopedTyped(ReflectionClass|string $reflection): ?string
{
$className = $reflection instanceof ReflectionClass
? $reflection->getName()
: $reflection;

if (array_key_exists($className, $this->checkedForSingletonOrScopedAttributes)) {
return $this->checkedForSingletonOrScopedAttributes[$className];
}

try {
$reflection = $reflection instanceof ReflectionClass
? $reflection
: new ReflectionClass($reflection);
} catch (ReflectionException) {
return $this->checkedForSingletonOrScopedAttributes[$className] = null;
}

$type = null;

if (! empty($reflection->getAttributes(Singleton::class))) {
$type = 'singleton';
} elseif (! empty($reflection->getAttributes(Scoped::class))) {
$type = 'scoped';
}

return $this->checkedForSingletonOrScopedAttributes[$className] = $type;
}







public function isAlias($name)
{
return isset($this->aliases[$name]);
}












public function bind($abstract, $concrete = null, $shared = false)
{
if ($abstract instanceof Closure) {
return $this->bindBasedOnClosureReturnTypes(
$abstract, $concrete, $shared
);
}

$this->dropStaleInstances($abstract);




if (is_null($concrete)) {
$concrete = $abstract;
}




if (! $concrete instanceof Closure) {
if (! is_string($concrete)) {
throw new TypeError(self::class.'::bind(): Argument #2 ($concrete) must be of type Closure|string|null');
}

$concrete = $this->getClosure($abstract, $concrete);
}

$this->bindings[$abstract] = ['concrete' => $concrete, 'shared' => $shared];




if ($this->resolved($abstract)) {
$this->rebound($abstract);
}
}








protected function getClosure($abstract, $concrete)
{
return function ($container, $parameters = []) use ($abstract, $concrete) {
if ($abstract == $concrete) {
return $container->build($concrete);
}

return $container->resolve(
$concrete, $parameters, raiseEvents: false
);
};
}







public function hasMethodBinding($method)
{
return isset($this->methodBindings[$method]);
}








public function bindMethod($method, $callback)
{
$this->methodBindings[$this->parseBindMethod($method)] = $callback;
}







protected function parseBindMethod($method)
{
if (is_array($method)) {
return $method[0].'@'.$method[1];
}

return $method;
}








public function callMethodBinding($method, $instance)
{
return call_user_func($this->methodBindings[$method], $instance, $this);
}









public function addContextualBinding($concrete, $abstract, $implementation)
{
$this->contextual[$concrete][$this->getAlias($abstract)] = $implementation;
}









public function bindIf($abstract, $concrete = null, $shared = false)
{
if (! $this->bound($abstract)) {
$this->bind($abstract, $concrete, $shared);
}
}








public function singleton($abstract, $concrete = null)
{
$this->bind($abstract, $concrete, true);
}








public function singletonIf($abstract, $concrete = null)
{
if (! $this->bound($abstract)) {
$this->singleton($abstract, $concrete);
}
}








public function scoped($abstract, $concrete = null)
{
$this->scopedInstances[] = $abstract;

$this->singleton($abstract, $concrete);
}








public function scopedIf($abstract, $concrete = null)
{
if (! $this->bound($abstract)) {
$this->scoped($abstract, $concrete);
}
}









protected function bindBasedOnClosureReturnTypes($abstract, $concrete = null, $shared = false)
{
$abstracts = $this->closureReturnTypes($abstract);

$concrete = $abstract;

foreach ($abstracts as $abstract) {
$this->bind($abstract, $concrete, $shared);
}
}









public function extend($abstract, Closure $closure)
{
$abstract = $this->getAlias($abstract);

if (isset($this->instances[$abstract])) {
$this->instances[$abstract] = $closure($this->instances[$abstract], $this);

$this->rebound($abstract);
} else {
$this->extenders[$abstract][] = $closure;

if ($this->resolved($abstract)) {
$this->rebound($abstract);
}
}
}

/**
@template






*/
public function instance($abstract, $instance)
{
$this->removeAbstractAlias($abstract);

$isBound = $this->bound($abstract);

unset($this->aliases[$abstract]);




$this->instances[$abstract] = $instance;

if ($isBound) {
$this->rebound($abstract);
}

return $instance;
}







protected function removeAbstractAlias($searched)
{
if (! isset($this->aliases[$searched])) {
return;
}

foreach ($this->abstractAliases as $abstract => $aliases) {
foreach ($aliases as $index => $alias) {
if ($alias == $searched) {
unset($this->abstractAliases[$abstract][$index]);
}
}
}
}








public function tag($abstracts, $tags)
{
$tags = is_array($tags) ? $tags : array_slice(func_get_args(), 1);

foreach ($tags as $tag) {
if (! isset($this->tags[$tag])) {
$this->tags[$tag] = [];
}

foreach ((array) $abstracts as $abstract) {
$this->tags[$tag][] = $abstract;
}
}
}







public function tagged($tag)
{
if (! isset($this->tags[$tag])) {
return [];
}

return new RewindableGenerator(function () use ($tag) {
foreach ($this->tags[$tag] as $abstract) {
yield $this->make($abstract);
}
}, count($this->tags[$tag]));
}










public function alias($abstract, $alias)
{
if ($alias === $abstract) {
throw new LogicException("[{$abstract}] is aliased to itself.");
}

$this->removeAbstractAlias($alias);

$this->aliases[$alias] = $abstract;

$this->abstractAliases[$abstract][] = $alias;
}







public function rebinding($abstract, Closure $callback)
{
$this->reboundCallbacks[$abstract = $this->getAlias($abstract)][] = $callback;

if ($this->bound($abstract)) {
return $this->make($abstract);
}
}









public function refresh($abstract, $target, $method)
{
return $this->rebinding($abstract, function ($app, $instance) use ($target, $method) {
$target->{$method}($instance);
});
}







protected function rebound($abstract)
{
if (! $callbacks = $this->getReboundCallbacks($abstract)) {
return;
}

$instance = $this->make($abstract);

foreach ($callbacks as $callback) {
$callback($this, $instance);
}
}







protected function getReboundCallbacks($abstract)
{
return $this->reboundCallbacks[$abstract] ?? [];
}






public function wrap(Closure $callback, array $parameters = [])
{
return fn () => $this->call($callback, $parameters);
}











public function call($callback, array $parameters = [], $defaultMethod = null)
{
$pushedToBuildStack = false;

if (($className = $this->getClassForCallable($callback)) && ! in_array(
$className,
$this->buildStack,
true
)) {
$this->buildStack[] = $className;

$pushedToBuildStack = true;
}

$result = BoundMethod::call($this, $callback, $parameters, $defaultMethod);

if ($pushedToBuildStack) {
array_pop($this->buildStack);
}

return $result;
}







protected function getClassForCallable($callback)
{
if (is_callable($callback) &&
! ($reflector = new ReflectionFunction($callback(...)))->isAnonymous()) {
return $reflector->getClosureScopeClass()->name ?? false;
}

return false;
}

/**
@template





*/
public function factory($abstract)
{
return fn () => $this->make($abstract);
}

/**
@template







*/
public function makeWith($abstract, array $parameters = [])
{
return $this->make($abstract, $parameters);
}

/**
@template







*/
public function make($abstract, array $parameters = [])
{
return $this->resolve($abstract, $parameters);
}

/**
@template





*/
public function get(string $id)
{
try {
return $this->resolve($id);
} catch (Exception $e) {
if ($this->has($id) || $e instanceof CircularDependencyException) {
throw $e;
}

throw new EntryNotFoundException($id, is_int($e->getCode()) ? $e->getCode() : 0, $e);
}
}

/**
@template










*/
protected function resolve($abstract, $parameters = [], $raiseEvents = true)
{
$abstract = $this->getAlias($abstract);




if ($raiseEvents) {
$this->fireBeforeResolvingCallbacks($abstract, $parameters);
}

$concrete = $this->getContextualConcrete($abstract);

$needsContextualBuild = ! empty($parameters) || ! is_null($concrete);




if (isset($this->instances[$abstract]) && ! $needsContextualBuild) {
return $this->instances[$abstract];
}

$this->with[] = $parameters;

if (is_null($concrete)) {
$concrete = $this->getConcrete($abstract);
}




$object = $this->isBuildable($concrete, $abstract)
? $this->build($concrete)
: $this->make($concrete);




foreach ($this->getExtenders($abstract) as $extender) {
$object = $extender($object, $this);
}




if ($this->isShared($abstract) && ! $needsContextualBuild) {
$this->instances[$abstract] = $object;
}

if ($raiseEvents) {
$this->fireResolvingCallbacks($abstract, $object);
}




if (! $needsContextualBuild) {
$this->resolved[$abstract] = true;
}

array_pop($this->with);

return $object;
}







protected function getConcrete($abstract)
{



if (isset($this->bindings[$abstract])) {
return $this->bindings[$abstract]['concrete'];
}

if ($this->environmentResolver === null ||
($this->checkedForAttributeBindings[$abstract] ?? false) || ! is_string($abstract)) {
return $abstract;
}

return $this->getConcreteBindingFromAttributes($abstract);
}







protected function getConcreteBindingFromAttributes($abstract)
{
$this->checkedForAttributeBindings[$abstract] = true;

try {
$reflected = new ReflectionClass($abstract);
} catch (ReflectionException) {
return $abstract;
}

$bindAttributes = $reflected->getAttributes(Bind::class);

if ($bindAttributes === []) {
return $abstract;
}

$concrete = $maybeConcrete = null;

foreach ($bindAttributes as $reflectedAttribute) {
$instance = $reflectedAttribute->newInstance();

if ($instance->environments === ['*']) {
$maybeConcrete = $instance->concrete;

continue;
}

if ($this->currentEnvironmentIs($instance->environments)) {
$concrete = $instance->concrete;

break;
}
}

if ($maybeConcrete !== null && $concrete === null) {
$concrete = $maybeConcrete;
}

if ($concrete === null) {
return $abstract;
}

match ($this->getScopedTyped($reflected)) {
'scoped' => $this->scoped($abstract, $concrete),
'singleton' => $this->singleton($abstract, $concrete),
null => $this->bind($abstract, $concrete),
};

return $this->bindings[$abstract]['concrete'];
}







protected function getContextualConcrete($abstract)
{
if (! is_null($binding = $this->findInContextualBindings($abstract))) {
return $binding;
}




if (empty($this->abstractAliases[$abstract])) {
return;
}

foreach ($this->abstractAliases[$abstract] as $alias) {
if (! is_null($binding = $this->findInContextualBindings($alias))) {
return $binding;
}
}
}







protected function findInContextualBindings($abstract)
{
return $this->contextual[end($this->buildStack)][$abstract] ?? null;
}








protected function isBuildable($concrete, $abstract)
{
return $concrete === $abstract || $concrete instanceof Closure;
}

/**
@template








*/
public function build($concrete)
{



if ($concrete instanceof Closure) {
$this->buildStack[] = spl_object_hash($concrete);

try {
return $concrete($this, $this->getLastParameterOverride());
} finally {
array_pop($this->buildStack);
}
}

try {
$reflector = new ReflectionClass($concrete);
} catch (ReflectionException $e) {
throw new BindingResolutionException("Target class [$concrete] does not exist.", 0, $e);
}




if (! $reflector->isInstantiable()) {
return $this->notInstantiable($concrete);
}

if (is_a($concrete, SelfBuilding::class, true) &&
! in_array($concrete, $this->buildStack, true)) {
return $this->buildSelfBuildingInstance($concrete, $reflector);
}

$this->buildStack[] = $concrete;

$constructor = $reflector->getConstructor();




if (is_null($constructor)) {
array_pop($this->buildStack);

$this->fireAfterResolvingAttributeCallbacks(
$reflector->getAttributes(), $instance = new $concrete
);

return $instance;
}

$dependencies = $constructor->getParameters();




try {
$instances = $this->resolveDependencies($dependencies);
} finally {
array_pop($this->buildStack);
}

$this->fireAfterResolvingAttributeCallbacks(
$reflector->getAttributes(), $instance = new $concrete(...$instances)
);

return $instance;
}

/**
@template








*/
protected function buildSelfBuildingInstance($concrete, $reflector)
{
if (! method_exists($concrete, 'newInstance')) {
throw new BindingResolutionException("No newInstance method exists for [$concrete].");
}

$this->buildStack[] = $concrete;

$instance = $this->call([$concrete, 'newInstance']);

array_pop($this->buildStack);

$this->fireAfterResolvingAttributeCallbacks(
$reflector->getAttributes(), $instance
);

return $instance;
}









protected function resolveDependencies(array $dependencies)
{
$results = [];

foreach ($dependencies as $dependency) {



if ($this->hasParameterOverride($dependency)) {
$results[] = $this->getParameterOverride($dependency);

continue;
}

$result = null;

if (! is_null($attribute = Util::getContextualAttributeFromDependency($dependency))) {
$result = $this->resolveFromAttribute($attribute);
}




$result ??= is_null($className = Util::getParameterClassName($dependency))
? $this->resolvePrimitive($dependency)
: $this->resolveClass($dependency, $className);

$this->fireAfterResolvingAttributeCallbacks($dependency->getAttributes(), $result);

if ($dependency->isVariadic()) {
$results = array_merge($results, $result);
} else {
$results[] = $result;
}
}

return $results;
}







protected function hasParameterOverride($dependency)
{
return array_key_exists(
$dependency->name, $this->getLastParameterOverride()
);
}







protected function getParameterOverride($dependency)
{
return $this->getLastParameterOverride()[$dependency->name];
}






protected function getLastParameterOverride()
{
return count($this->with) ? array_last($this->with) : [];
}








protected function resolvePrimitive(ReflectionParameter $parameter)
{
if (! is_null($concrete = $this->getContextualConcrete('$'.$parameter->getName()))) {
return Util::unwrapIfClosure($concrete, $this);
}

if ($parameter->isDefaultValueAvailable()) {
return $parameter->getDefaultValue();
}

if ($parameter->isVariadic()) {
return [];
}

if ($parameter->hasType() && $parameter->allowsNull()) {
return null;
}

$this->unresolvablePrimitive($parameter);
}








protected function resolveClass(ReflectionParameter $parameter, ?string $className = null)
{
$className ??= Util::getParameterClassName($parameter);




if ($parameter->isDefaultValueAvailable() &&
! $this->bound($className) &&
$this->findInContextualBindings($className) === null) {
return $parameter->getDefaultValue();
}

try {
return $parameter->isVariadic()
? $this->resolveVariadicClass($parameter)
: $this->make($className);
}




catch (BindingResolutionException $e) {
if ($parameter->isVariadic()) {
array_pop($this->with);

return [];
}

throw $e;
}
}






protected function resolveVariadicClass(ReflectionParameter $parameter)
{
$className = Util::getParameterClassName($parameter);

$abstract = $this->getAlias($className);

if (! is_array($concrete = $this->getContextualConcrete($abstract))) {
return $this->make($className);
}

return array_map(fn ($abstract) => $this->resolve($abstract), $concrete);
}






public function resolveFromAttribute(ReflectionAttribute $attribute)
{
$handler = $this->contextualAttributes[$attribute->getName()] ?? null;

$instance = $attribute->newInstance();

if (is_null($handler) && method_exists($instance, 'resolve')) {
$handler = $instance->resolve(...);
}

if (is_null($handler)) {
throw new BindingResolutionException("Contextual binding attribute [{$attribute->getName()}] has no registered handler.");
}

return $handler($instance, $this);
}









protected function notInstantiable($concrete)
{
if (! empty($this->buildStack)) {
$previous = implode(', ', $this->buildStack);

$message = "Target [$concrete] is not instantiable while building [$previous].";
} else {
$message = "Target [$concrete] is not instantiable.";
}

throw new BindingResolutionException($message);
}








protected function unresolvablePrimitive(ReflectionParameter $parameter)
{
$message = "Unresolvable dependency resolving [$parameter] in class {$parameter->getDeclaringClass()->getName()}";

throw new BindingResolutionException($message);
}







public function beforeResolving($abstract, ?Closure $callback = null)
{
if (is_string($abstract)) {
$abstract = $this->getAlias($abstract);
}

if ($abstract instanceof Closure && is_null($callback)) {
$this->globalBeforeResolvingCallbacks[] = $abstract;
} else {
$this->beforeResolvingCallbacks[$abstract][] = $callback;
}
}







public function resolving($abstract, ?Closure $callback = null)
{
if (is_string($abstract)) {
$abstract = $this->getAlias($abstract);
}

if (is_null($callback) && $abstract instanceof Closure) {
$this->globalResolvingCallbacks[] = $abstract;
} else {
$this->resolvingCallbacks[$abstract][] = $callback;
}
}







public function afterResolving($abstract, ?Closure $callback = null)
{
if (is_string($abstract)) {
$abstract = $this->getAlias($abstract);
}

if ($abstract instanceof Closure && is_null($callback)) {
$this->globalAfterResolvingCallbacks[] = $abstract;
} else {
$this->afterResolvingCallbacks[$abstract][] = $callback;
}
}






public function afterResolvingAttribute(string $attribute, \Closure $callback)
{
$this->afterResolvingAttributeCallbacks[$attribute][] = $callback;
}








protected function fireBeforeResolvingCallbacks($abstract, $parameters = [])
{
$this->fireBeforeCallbackArray($abstract, $parameters, $this->globalBeforeResolvingCallbacks);

foreach ($this->beforeResolvingCallbacks as $type => $callbacks) {
if ($type === $abstract || is_subclass_of($abstract, $type)) {
$this->fireBeforeCallbackArray($abstract, $parameters, $callbacks);
}
}
}








protected function fireBeforeCallbackArray($abstract, $parameters, array $callbacks)
{
foreach ($callbacks as $callback) {
$callback($abstract, $parameters, $this);
}
}








protected function fireResolvingCallbacks($abstract, $object)
{
$this->fireCallbackArray($object, $this->globalResolvingCallbacks);

$this->fireCallbackArray(
$object, $this->getCallbacksForType($abstract, $object, $this->resolvingCallbacks)
);

$this->fireAfterResolvingCallbacks($abstract, $object);
}








protected function fireAfterResolvingCallbacks($abstract, $object)
{
$this->fireCallbackArray($object, $this->globalAfterResolvingCallbacks);

$this->fireCallbackArray(
$object, $this->getCallbacksForType($abstract, $object, $this->afterResolvingCallbacks)
);
}








public function fireAfterResolvingAttributeCallbacks(array $attributes, $object)
{
foreach ($attributes as $attribute) {
if (is_a($attribute->getName(), ContextualAttribute::class, true)) {
$instance = $attribute->newInstance();

if (method_exists($instance, 'after')) {
$instance->after($instance, $object, $this);
}
}

$callbacks = $this->getCallbacksForType(
$attribute->getName(), $object, $this->afterResolvingAttributeCallbacks
);

foreach ($callbacks as $callback) {
$callback($attribute->newInstance(), $object, $this);
}
}
}








protected function getCallbacksForType($abstract, $object, array $callbacksPerType)
{
$results = [];

foreach ($callbacksPerType as $type => $callbacks) {
if ($type === $abstract || $object instanceof $type) {
$results = array_merge($results, $callbacks);
}
}

return $results;
}







protected function fireCallbackArray($object, array $callbacks)
{
foreach ($callbacks as $callback) {
$callback($object, $this);
}
}






public function currentlyResolving()
{
return array_last($this->buildStack) ?: null;
}






public function getBindings()
{
return $this->bindings;
}







public function getAlias($abstract)
{
return isset($this->aliases[$abstract])
? $this->getAlias($this->aliases[$abstract])
: $abstract;
}







protected function getExtenders($abstract)
{
return $this->extenders[$this->getAlias($abstract)] ?? [];
}







public function forgetExtenders($abstract)
{
unset($this->extenders[$this->getAlias($abstract)]);
}







protected function dropStaleInstances($abstract)
{
unset($this->instances[$abstract], $this->aliases[$abstract]);
}







public function forgetInstance($abstract)
{
unset($this->instances[$abstract]);
}






public function forgetInstances()
{
$this->instances = [];
}






public function forgetScopedInstances()
{
foreach ($this->scopedInstances as $scoped) {
if ($scoped instanceof Closure) {
foreach ($this->closureReturnTypes($scoped) as $type) {
unset($this->instances[$type]);
}
} else {
unset($this->instances[$scoped]);
}
}
}







public function resolveEnvironmentUsing(?callable $callback)
{
$this->environmentResolver = $callback;
}







public function currentEnvironmentIs($environments)
{
return $this->environmentResolver === null
? false
: call_user_func($this->environmentResolver, $environments);
}






public function flush()
{
$this->aliases = [];
$this->resolved = [];
$this->bindings = [];
$this->instances = [];
$this->abstractAliases = [];
$this->scopedInstances = [];
$this->checkedForAttributeBindings = [];
$this->checkedForSingletonOrScopedAttributes = [];
}






public static function getInstance()
{
return static::$instance ??= new static;
}






public static function setInstance(?ContainerContract $container = null)
{
return static::$instance = $container;
}






public function offsetExists($key): bool
{
return $this->bound($key);
}






public function offsetGet($key): mixed
{
return $this->make($key);
}







public function offsetSet($key, $value): void
{
$this->bind($key, $value instanceof Closure ? $value : fn () => $value);
}






public function offsetUnset($key): void
{
unset($this->bindings[$key], $this->instances[$key], $this->resolved[$key]);
}







public function __get($key)
{
return $this[$key];
}








public function __set($key, $value)
{
$this[$key] = $value;
}
}
