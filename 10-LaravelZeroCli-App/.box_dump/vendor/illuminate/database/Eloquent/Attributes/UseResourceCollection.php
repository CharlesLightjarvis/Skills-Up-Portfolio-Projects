<?php

namespace Illuminate\Database\Eloquent\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class UseResourceCollection
{





public function __construct(public string $class)
{
}
}
