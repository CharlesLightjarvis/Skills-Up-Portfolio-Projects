<?php

namespace Illuminate\Database\Eloquent\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class UseEloquentBuilder
{





public function __construct(public string $builderClass)
{
}
}
