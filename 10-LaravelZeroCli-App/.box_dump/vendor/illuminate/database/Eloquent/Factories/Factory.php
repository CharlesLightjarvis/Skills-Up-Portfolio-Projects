<?php

namespace Illuminate\Database\Eloquent\Factories;

use Closure;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Traits\Macroable;
use Throwable;
use UnitEnum;

use function Illuminate\Support\enum_value;

/**
@template


*/
abstract class Factory
{
use Conditionable, ForwardsCalls, Macroable {
__call as macroCall;
}






protected $model;






protected $count;






protected $states;






protected $has;






protected $for;






protected $recycle;






protected $afterMaking;






protected $afterCreating;






protected $expandRelationships = true;






protected $excludeRelationships = [];






protected $connection;






protected $faker;






public static $namespace = 'Database\\Factories\\';






protected static $modelNameResolver;






protected static $modelNameResolvers = [];






protected static $factoryNameResolver;






protected static $expandRelationshipsByDefault = true;















public function __construct(
$count = null,
?Collection $states = null,
?Collection $has = null,
?Collection $for = null,
?Collection $afterMaking = null,
?Collection $afterCreating = null,
$connection = null,
?Collection $recycle = null,
?bool $expandRelationships = null,
array $excludeRelationships = [],
) {
$this->count = $count;
$this->states = $states ?? new Collection;
$this->has = $has ?? new Collection;
$this->for = $for ?? new Collection;
$this->afterMaking = $afterMaking ?? new Collection;
$this->afterCreating = $afterCreating ?? new Collection;
$this->connection = $connection;
$this->recycle = $recycle ?? new Collection;
$this->faker = $this->withFaker();
$this->expandRelationships = $expandRelationships ?? self::$expandRelationshipsByDefault;
$this->excludeRelationships = $excludeRelationships;
}






abstract public function definition();







public static function new($attributes = [])
{
return (new static)->state($attributes)->configure();
}







public static function times(int $count)
{
return static::new()->count($count);
}






public function configure()
{
return $this;
}








public function raw($attributes = [], ?Model $parent = null)
{
if ($this->count === null) {
return $this->state($attributes)->getExpandedAttributes($parent);
}

return array_map(function () use ($attributes, $parent) {
return $this->state($attributes)->getExpandedAttributes($parent);
}, range(1, $this->count));
}







public function createOne($attributes = [])
{
return $this->count(null)->create($attributes);
}







public function createOneQuietly($attributes = [])
{
return $this->count(null)->createQuietly($attributes);
}







public function createMany(int|iterable|null $records = null)
{
$records ??= ($this->count ?? 1);

$this->count = null;

if (is_numeric($records)) {
$records = array_fill(0, $records, []);
}

return new EloquentCollection(
(new Collection($records))->map(function ($record) {
return $this->state($record)->create();
})
);
}







public function createManyQuietly(int|iterable|null $records = null)
{
return Model::withoutEvents(fn () => $this->createMany($records));
}








public function create($attributes = [], ?Model $parent = null)
{
if (! empty($attributes)) {
return $this->state($attributes)->create([], $parent);
}

$results = $this->make($attributes, $parent);

if ($results instanceof Model) {
$this->store(new Collection([$results]));

$this->callAfterCreating(new Collection([$results]), $parent);
} else {
$this->store($results);

$this->callAfterCreating($results, $parent);
}

return $results;
}








public function createQuietly($attributes = [], ?Model $parent = null)
{
return Model::withoutEvents(fn () => $this->create($attributes, $parent));
}








public function lazy(array $attributes = [], ?Model $parent = null)
{
return fn () => $this->create($attributes, $parent);
}







protected function store(Collection $results)
{
$results->each(function ($model) {
if (! isset($this->connection)) {
$model->setConnection($model->newQueryWithoutScopes()->getConnection()->getName());
}

$model->save();

foreach ($model->getRelations() as $name => $items) {
if ($items instanceof Enumerable && $items->isEmpty()) {
$model->unsetRelation($name);
}
}

$this->createChildren($model);
});
}







protected function createChildren(Model $model)
{
Model::unguarded(function () use ($model) {
$this->has->each(function ($has) use ($model) {
$has->recycle($this->recycle)->createFor($model);
});
});
}







public function makeOne($attributes = [])
{
return $this->count(null)->make($attributes);
}








public function make($attributes = [], ?Model $parent = null)
{
$autoEagerLoadingEnabled = Model::isAutomaticallyEagerLoadingRelationships();

if ($autoEagerLoadingEnabled) {
Model::automaticallyEagerLoadRelationships(false);
}

try {
if (! empty($attributes)) {
return $this->state($attributes)->make([], $parent);
}

if ($this->count === null) {
return tap($this->makeInstance($parent), function ($instance) {
$this->callAfterMaking(new Collection([$instance]));
});
}

if ($this->count < 1) {
return $this->newModel()->newCollection();
}

$instances = $this->newModel()->newCollection(array_map(function () use ($parent) {
return $this->makeInstance($parent);
}, range(1, $this->count)));

$this->callAfterMaking($instances);

return $instances;
} finally {
Model::automaticallyEagerLoadRelationships($autoEagerLoadingEnabled);
}
}







public function makeMany(iterable|int|null $records = null)
{
$records ??= ($this->count ?? 1);

$this->count = null;

if (is_numeric($records)) {
$records = array_fill(0, $records, []);
}

return new EloquentCollection(
(new Collection($records))->map(function ($record) {
return $this->state($record)->make();
})
);
}








public function insert(array $attributes = [], ?Model $parent = null): void
{
$made = $this->make($attributes, $parent);

$madeCollection = $made instanceof Collection
? $made
: $this->newModel()->newCollection([$made]);

$model = $madeCollection->first();

if (isset($this->connection)) {
$model->setConnection($this->connection);
}

$query = $model->newQueryWithoutScopes();

$query->fillAndInsert(
$madeCollection->withoutAppends()
->setHidden([])
->map(static fn (Model $model) => $model->attributesToArray())
->all()
);
}







protected function makeInstance(?Model $parent)
{
return Model::unguarded(function () use ($parent) {
return tap($this->newModel($this->getExpandedAttributes($parent)), function ($instance) {
if (isset($this->connection)) {
$instance->setConnection($this->connection);
}
});
});
}







protected function getExpandedAttributes(?Model $parent)
{
return $this->expandAttributes($this->getRawAttributes($parent));
}







protected function getRawAttributes(?Model $parent)
{
return $this->states->pipe(function ($states) {
return $this->for->isEmpty() ? $states : new Collection(array_merge([function () {
return $this->parentResolvers();
}], $states->all()));
})->reduce(function ($carry, $state) use ($parent) {
if ($state instanceof Closure) {
$state = $state->bindTo($this);
}

return array_merge($carry, $state($carry, $parent));
}, $this->definition());
}






protected function parentResolvers()
{
return $this->for
->map(fn (BelongsToRelationship $for) => $for->recycle($this->recycle)->attributesFor($this->newModel()))
->collapse()
->all();
}







protected function expandAttributes(array $definition)
{
return (new Collection($definition))
->map($evaluateRelations = function ($attribute, $key) {
if (! $this->expandRelationships && $attribute instanceof self) {
$attribute = null;
} elseif ($attribute instanceof self &&
array_intersect([$attribute->modelName(), $key], $this->excludeRelationships)) {
$attribute = null;
} elseif ($attribute instanceof self) {
$attribute = $this->getRandomRecycledModel($attribute->modelName())?->getKey()
?? $attribute->recycle($this->recycle)->create()->getKey();
} elseif ($attribute instanceof Model) {
$attribute = $attribute->getKey();
}

return $attribute;
})
->map(function ($attribute, $key) use (&$definition, $evaluateRelations) {
if (is_callable($attribute) && ! is_string($attribute) && ! is_array($attribute)) {
$attribute = $attribute($definition);
}

$attribute = $evaluateRelations($attribute, $key);

$definition[$key] = $attribute;

return $attribute;
})
->all();
}







public function state($state)
{
return $this->newInstance([
'states' => $this->states->concat([
is_callable($state) ? $state : fn () => $state,
]),
]);
}







public function prependState($state)
{
return $this->newInstance([
'states' => $this->states->prepend(
is_callable($state) ? $state : fn () => $state,
),
]);
}








public function set($key, $value)
{
return $this->state([$key => $value]);
}







public function sequence(...$sequence)
{
return $this->state(new Sequence(...$sequence));
}







public function forEachSequence(...$sequence)
{
return $this->state(new Sequence(...$sequence))->count(count($sequence));
}







public function crossJoinSequence(...$sequence)
{
return $this->state(new CrossJoinSequence(...$sequence));
}








public function has(self $factory, $relationship = null)
{
return $this->newInstance([
'has' => $this->has->concat([new Relationship(
$factory, $relationship ?? $this->guessRelationship($factory->modelName())
)]),
]);
}







protected function guessRelationship(string $related)
{
$guess = Str::camel(Str::plural(class_basename($related)));

return method_exists($this->modelName(), $guess) ? $guess : Str::singular($guess);
}









public function hasAttached($factory, $pivot = [], $relationship = null)
{
return $this->newInstance([
'has' => $this->has->concat([new BelongsToManyRelationship(
$factory,
$pivot,
$relationship ?? Str::camel(Str::plural(class_basename(
$factory instanceof Factory
? $factory->modelName()
: Collection::wrap($factory)->first()
)))
)]),
]);
}








public function for($factory, $relationship = null)
{
return $this->newInstance(['for' => $this->for->concat([new BelongsToRelationship(
$factory,
$relationship ?? Str::camel(class_basename(
$factory instanceof Factory ? $factory->modelName() : $factory
))
)])]);
}







public function recycle($model)
{

return $this->newInstance([
'recycle' => $this->recycle
->flatten()
->merge(
Collection::wrap($model instanceof Model ? func_get_args() : $model)
->flatten()
)->groupBy(fn ($model) => get_class($model)),
]);
}

/**
@template





*/
public function getRandomRecycledModel($modelClassName)
{
return $this->recycle->get($modelClassName)?->random();
}







public function afterMaking(Closure $callback)
{
return $this->newInstance(['afterMaking' => $this->afterMaking->concat([$callback])]);
}







public function afterCreating(Closure $callback)
{
return $this->newInstance(['afterCreating' => $this->afterCreating->concat([$callback])]);
}






public function withoutAfterMaking()
{
return $this->newInstance(['afterMaking' => new Collection]);
}






public function withoutAfterCreating()
{
return $this->newInstance(['afterCreating' => new Collection]);
}







protected function callAfterMaking(Collection $instances)
{
$instances->each(function ($model) {
$this->afterMaking->each(function ($callback) use ($model) {
$callback($model);
});
});
}








protected function callAfterCreating(Collection $instances, ?Model $parent = null)
{
$instances->each(function ($model) use ($parent) {
$this->afterCreating->each(function ($callback) use ($model, $parent) {
$callback($model, $parent);
});
});
}







public function count(?int $count)
{
return $this->newInstance(['count' => $count]);
}







public function withoutParents($parents = [])
{
return $this->newInstance(! $parents ? ['expandRelationships' => false] : ['excludeRelationships' => $parents]);
}






public function getConnectionName()
{
return enum_value($this->connection);
}







public function connection(UnitEnum|string|null $connection)
{
return $this->newInstance(['connection' => $connection]);
}







protected function newInstance(array $arguments = [])
{
return new static(...array_values(array_merge([
'count' => $this->count,
'states' => $this->states,
'has' => $this->has,
'for' => $this->for,
'afterMaking' => $this->afterMaking,
'afterCreating' => $this->afterCreating,
'connection' => $this->connection,
'recycle' => $this->recycle,
'expandRelationships' => $this->expandRelationships,
'excludeRelationships' => $this->excludeRelationships,
], $arguments)));
}







public function newModel(array $attributes = [])
{
$model = $this->modelName();

return new $model($attributes);
}






public function modelName()
{
if ($this->model !== null) {
return $this->model;
}

$resolver = static::$modelNameResolvers[static::class] ?? static::$modelNameResolvers[self::class] ?? static::$modelNameResolver ?? function (self $factory) {
$namespacedFactoryBasename = Str::replaceLast(
'Factory', '', Str::replaceFirst(static::$namespace, '', $factory::class)
);

$factoryBasename = Str::replaceLast('Factory', '', class_basename($factory));

$appNamespace = static::appNamespace();

return class_exists($appNamespace.'Models\\'.$namespacedFactoryBasename)
? $appNamespace.'Models\\'.$namespacedFactoryBasename
: $appNamespace.$factoryBasename;
};

return $resolver($this);
}







public static function guessModelNamesUsing(callable $callback)
{
static::$modelNameResolvers[static::class] = $callback;
}







public static function useNamespace(string $namespace)
{
static::$namespace = $namespace;
}

/**
@template





*/
public static function factoryForModel(string $modelName)
{
$factory = static::resolveFactoryName($modelName);

return $factory::new();
}







public static function guessFactoryNamesUsing(callable $callback)
{
static::$factoryNameResolver = $callback;
}






public static function expandRelationshipsByDefault()
{
static::$expandRelationshipsByDefault = true;
}






public static function dontExpandRelationshipsByDefault()
{
static::$expandRelationshipsByDefault = false;
}






protected function withFaker()
{
if (! class_exists(Generator::class)) {
return;
}

return Container::getInstance()->make(Generator::class);
}

/**
@template





*/
public static function resolveFactoryName(string $modelName)
{
$resolver = static::$factoryNameResolver ?? function (string $modelName) {
$appNamespace = static::appNamespace();

$modelName = Str::startsWith($modelName, $appNamespace.'Models\\')
? Str::after($modelName, $appNamespace.'Models\\')
: Str::after($modelName, $appNamespace);

return static::$namespace.$modelName.'Factory';
};

return $resolver($modelName);
}






protected static function appNamespace()
{
try {
return Container::getInstance()
->make(Application::class)
->getNamespace();
} catch (Throwable) {
return 'App\\';
}
}






public static function flushState()
{
static::$modelNameResolver = null;
static::$modelNameResolvers = [];
static::$factoryNameResolver = null;
static::$namespace = 'Database\\Factories\\';
static::$expandRelationshipsByDefault = true;
}








public function __call($method, $parameters)
{
if (static::hasMacro($method)) {
return $this->macroCall($method, $parameters);
}

if ($method === 'trashed' && $this->modelName()::isSoftDeletable()) {
return $this->state([
$this->newModel()->getDeletedAtColumn() => $parameters[0] ?? Carbon::now()->subDay(),
]);
}

if (! Str::startsWith($method, ['for', 'has'])) {
static::throwBadMethodCallException($method);
}

$relationship = Str::camel(Str::substr($method, 3));

$relatedModel = get_class($this->newModel()->{$relationship}()->getRelated());

if (method_exists($relatedModel, 'newFactory')) {
$factory = $relatedModel::newFactory() ?? static::factoryForModel($relatedModel);
} else {
$factory = static::factoryForModel($relatedModel);
}

if (str_starts_with($method, 'for')) {
return $this->for($factory->state($parameters[0] ?? []), $relationship);
} elseif (str_starts_with($method, 'has')) {
return $this->has(
$factory
->count(is_numeric($parameters[0] ?? null) ? $parameters[0] : 1)
->state((is_callable($parameters[0] ?? null) || is_array($parameters[0] ?? null)) ? $parameters[0] : ($parameters[1] ?? [])),
$relationship
);
}
}
}
