<?php

namespace Illuminate\Database\Eloquent;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use SplFileObject;

use function Illuminate\Support\enum_value;

class ModelInspector
{





protected $relationMethods = [
'hasMany',
'hasManyThrough',
'hasOneThrough',
'belongsToMany',
'hasOne',
'belongsTo',
'morphOne',
'morphTo',
'morphMany',
'morphToMany',
'morphedByMany',
];






public function __construct(protected Application $app)
{
}










public function inspect($model, $connection = null)
{
$class = $this->qualifyModel($model);


$model = $this->app->make($class);

if ($connection !== null) {
$model->setConnection($connection);
}

return [
'class' => get_class($model),
'database' => $model->getConnection()->getName(),
'table' => $model->getConnection()->getTablePrefix().$model->getTable(),
'policy' => $this->getPolicy($model),
'attributes' => $this->getAttributes($model),
'relations' => $this->getRelations($model),
'events' => $this->getEvents($model),
'observers' => $this->getObservers($model),
'collection' => $this->getCollectedBy($model),
'builder' => $this->getBuilder($model),
'resource' => $this->getResource($model),
];
}







protected function getAttributes($model)
{
$connection = $model->getConnection();
$schema = $connection->getSchemaBuilder();
$table = $model->getTable();
$columns = $schema->getColumns($table);
$indexes = $schema->getIndexes($table);

return (new BaseCollection($columns))
->map(fn ($column) => [
'name' => $column['name'],
'type' => $column['type'],
'increments' => $column['auto_increment'],
'nullable' => $column['nullable'],
'default' => $this->getColumnDefault($column, $model),
'unique' => $this->columnIsUnique($column['name'], $indexes),
'fillable' => $model->isFillable($column['name']),
'hidden' => $this->attributeIsHidden($column['name'], $model),
'appended' => null,
'cast' => $this->getCastType($column['name'], $model),
])
->merge($this->getVirtualAttributes($model, $columns));
}








protected function getVirtualAttributes($model, $columns)
{
$class = new ReflectionClass($model);

return (new BaseCollection($class->getMethods()))
->reject(
fn (ReflectionMethod $method) => $method->isStatic()
|| $method->isAbstract()
|| $method->getDeclaringClass()->getName() === Model::class
)
->mapWithKeys(function (ReflectionMethod $method) use ($model) {
if (preg_match('/^get(.+)Attribute$/', $method->getName(), $matches) === 1) {
return [Str::snake($matches[1]) => 'accessor'];
} elseif ($model->hasAttributeMutator($method->getName())) {
return [Str::snake($method->getName()) => 'attribute'];
} else {
return [];
}
})
->reject(fn ($cast, $name) => (new BaseCollection($columns))->contains('name', $name))
->map(fn ($cast, $name) => [
'name' => $name,
'type' => null,
'increments' => false,
'nullable' => null,
'default' => null,
'unique' => null,
'fillable' => $model->isFillable($name),
'hidden' => $this->attributeIsHidden($name, $model),
'appended' => $model->hasAppended($name),
'cast' => $cast,
])
->values();
}







protected function getRelations($model)
{
return (new BaseCollection(get_class_methods($model)))
->map(fn ($method) => new ReflectionMethod($model, $method))
->reject(
fn (ReflectionMethod $method) => $method->isStatic()
|| $method->isAbstract()
|| $method->getDeclaringClass()->getName() === Model::class
|| $method->getNumberOfParameters() > 0
)
->filter(function (ReflectionMethod $method) {
if ($method->getReturnType() instanceof ReflectionNamedType
&& is_subclass_of($method->getReturnType()->getName(), Relation::class)) {
return true;
}

$file = new SplFileObject($method->getFileName());
$file->seek($method->getStartLine() - 1);
$code = '';
while ($file->key() < $method->getEndLine()) {
$code .= trim($file->current());
$file->next();
}

return (new BaseCollection($this->relationMethods))
->contains(fn ($relationMethod) => str_contains($code, '$this->'.$relationMethod.'('));
})
->map(function (ReflectionMethod $method) use ($model) {
$relation = $method->invoke($model);

if (! $relation instanceof Relation) {
return null;
}

return [
'name' => $method->getName(),
'type' => Str::afterLast(get_class($relation), '\\'),
'related' => get_class($relation->getRelated()),
];
})
->filter()
->values();
}







protected function getPolicy($model)
{
$policy = Gate::getPolicyFor($model::class);

return $policy ? $policy::class : null;
}







protected function getEvents($model)
{
return (new BaseCollection($model->dispatchesEvents()))
->map(fn (string $class, string $event) => [
'event' => $event,
'class' => $class,
])->values();
}









protected function getObservers($model)
{
$listeners = $this->app->make('events')->getRawListeners();


$listeners = array_filter($listeners, function ($v, $key) use ($model) {
return Str::startsWith($key, 'eloquent.') && Str::endsWith($key, $model::class);
}, ARRAY_FILTER_USE_BOTH);


$extractVerb = function ($key) {
preg_match('/eloquent.([a-zA-Z]+)\: /', $key, $matches);

return $matches[1] ?? '?';
};

$formatted = [];

foreach ($listeners as $key => $observerMethods) {
$formatted[] = [
'event' => $extractVerb($key),
'observer' => array_map(fn ($obs) => is_string($obs) ? $obs : 'Closure', $observerMethods),
];
}

return new BaseCollection($formatted);
}







protected function getCollectedBy($model)
{
return $model->newCollection()::class;
}

/**
@template





*/
protected function getBuilder($model)
{
return $model->newQuery()::class;
}







protected function getResource($model)
{
return rescue(static fn () => $model->toResource()::class, null, false);
}









protected function qualifyModel(string $model)
{
if (str_contains($model, '\\') && class_exists($model)) {
return $model;
}

$model = ltrim($model, '\\/');

$model = str_replace('/', '\\', $model);

$rootNamespace = $this->app->getNamespace();

if (Str::startsWith($model, $rootNamespace)) {
return $model;
}

return is_dir(app_path('Models'))
? $rootNamespace.'Models\\'.$model
: $rootNamespace.$model;
}








protected function getCastType($column, $model)
{
if ($model->hasGetMutator($column) || $model->hasSetMutator($column)) {
return 'accessor';
}

if ($model->hasAttributeMutator($column)) {
return 'attribute';
}

return $this->getCastsWithDates($model)->get($column) ?? null;
}







protected function getCastsWithDates($model)
{
return (new BaseCollection($model->getDates()))
->filter()
->flip()
->map(fn () => 'datetime')
->merge($model->getCasts());
}








protected function attributeIsHidden($attribute, $model)
{
if (count($model->getHidden()) > 0) {
return in_array($attribute, $model->getHidden());
}

if (count($model->getVisible()) > 0) {
return ! in_array($attribute, $model->getVisible());
}

return false;
}








protected function getColumnDefault($column, $model)
{
$attributeDefault = $model->getAttributes()[$column['name']] ?? null;

return enum_value($attributeDefault) ?? $column['default'];
}








protected function columnIsUnique($column, $indexes)
{
return (new BaseCollection($indexes))->contains(
fn ($index) => count($index['columns']) === 1 && $index['columns'][0] === $column && $index['unique']
);
}
}
