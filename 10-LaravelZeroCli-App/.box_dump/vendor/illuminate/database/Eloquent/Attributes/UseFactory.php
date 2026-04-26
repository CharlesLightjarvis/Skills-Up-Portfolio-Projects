<?php

namespace Illuminate\Database\Eloquent\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class UseFactory
{





public function __construct(public string $factoryClass)
{
}
}
