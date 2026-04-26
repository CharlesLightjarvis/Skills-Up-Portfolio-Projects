<?php

namespace Illuminate\Container\Attributes;

use Attribute;
use InvalidArgumentException;
use UnitEnum;

use function Illuminate\Support\enum_value;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Bind
{





public string $concrete;






public array $environments = [];









public function __construct(
string $concrete,
string|array|UnitEnum $environments = ['*'],
) {
$environments = array_filter(is_array($environments) ? $environments : [$environments]);

if ($environments === []) {
throw new InvalidArgumentException('The environment property must be set and cannot be empty.');
}

$this->concrete = $concrete;

$this->environments = array_map(
fn ($environment) => enum_value($environment),
$environments,
);
}
}
