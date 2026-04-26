<?php

namespace Illuminate\Database\Eloquent;

interface Scope
{
/**
@template






*/
public function apply(Builder $builder, Model $model);
}
