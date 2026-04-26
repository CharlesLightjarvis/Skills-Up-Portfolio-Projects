<?php

namespace Illuminate\Database\Eloquent\Relations;

use Illuminate\Contracts\Database\Eloquent\SupportsPartialRelations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\CanBeOneOfMany;
use Illuminate\Database\Eloquent\Relations\Concerns\ComparesRelatedModels;
use Illuminate\Database\Eloquent\Relations\Concerns\InteractsWithDictionary;
use Illuminate\Database\Eloquent\Relations\Concerns\SupportsDefaultModels;
use Illuminate\Database\Query\JoinClause;

/**
@template
@template
@template
@extends

*/
class HasOneThrough extends HasOneOrManyThrough implements SupportsPartialRelations
{
use ComparesRelatedModels, CanBeOneOfMany, InteractsWithDictionary, SupportsDefaultModels;


public function getResults()
{
if (is_null($this->getParentKey())) {
return $this->getDefaultFor($this->farParent);
}

return $this->first() ?: $this->getDefaultFor($this->farParent);
}


public function initRelation(array $models, $relation)
{
foreach ($models as $model) {
$model->setRelation($relation, $this->getDefaultFor($model));
}

return $models;
}


public function match(array $models, EloquentCollection $results, $relation)
{
$dictionary = $this->buildDictionary($results);




foreach ($models as $model) {
$key = $this->getDictionaryKey($model->getAttribute($this->localKey));

if ($key !== null && isset($dictionary[$key])) {
$value = $dictionary[$key];

$model->setRelation(
$relation, reset($value)
);
}
}

return $models;
}


public function getRelationExistenceQuery(Builder $query, Builder $parentQuery, $columns = ['*'])
{
if ($this->isOneOfMany()) {
$this->mergeOneOfManyJoinsTo($query);
}

return parent::getRelationExistenceQuery($query, $parentQuery, $columns);
}


public function addOneOfManySubQueryConstraints(Builder $query, $column = null, $aggregate = null)
{
$query->addSelect([$this->getQualifiedFirstKeyName()]);


if ($this->getOneOfManySubQuery() !== null) {
$this->performJoin($query);
}
}


public function getOneOfManySubQuerySelectColumns()
{
return [$this->getQualifiedFirstKeyName()];
}


public function addOneOfManyJoinSubQueryConstraints(JoinClause $join)
{
$join->on($this->qualifySubSelectColumn($this->firstKey), '=', $this->getQualifiedFirstKeyName());
}







public function newRelatedInstanceFor(Model $parent)
{
return $this->related->newInstance();
}


protected function getRelatedKeyFrom(Model $model)
{
return $model->getAttribute($this->getForeignKeyName());
}


public function getParentKey()
{
return $this->farParent->getAttribute($this->localKey);
}
}
