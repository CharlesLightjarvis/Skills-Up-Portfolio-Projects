<?php

namespace Illuminate\Database\Eloquent\Relations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\Concerns\InteractsWithDictionary;

/**
@template
@template
@template
@extends

*/
class HasManyThrough extends HasOneOrManyThrough
{
use InteractsWithDictionary;






public function one()
{
return HasOneThrough::noConstraints(fn () => new HasOneThrough(
tap($this->getQuery(), fn (Builder $query) => $query->getQuery()->joins = []),
$this->farParent,
$this->throughParent,
$this->getFirstKeyName(),
$this->getForeignKeyName(),
$this->getLocalKeyName(),
$this->getSecondLocalKeyName(),
));
}


public function initRelation(array $models, $relation)
{
foreach ($models as $model) {
$model->setRelation($relation, $this->related->newCollection());
}

return $models;
}


public function match(array $models, EloquentCollection $results, $relation)
{
$dictionary = $this->buildDictionary($results);




foreach ($models as $model) {
$key = $this->getDictionaryKey($model->getAttribute($this->localKey));

if ($key !== null && isset($dictionary[$key])) {
$model->setRelation(
$relation, $this->related->newCollection($dictionary[$key])
);
}
}

return $models;
}


public function getResults()
{
return ! is_null($this->farParent->{$this->localKey})
? $this->get()
: $this->related->newCollection();
}
}
