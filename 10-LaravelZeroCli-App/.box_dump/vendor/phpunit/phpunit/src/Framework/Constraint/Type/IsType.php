<?php declare(strict_types=1);








namespace PHPUnit\Framework\Constraint;

use function gettype;
use function is_array;
use function is_bool;
use function is_callable;
use function is_float;
use function is_int;
use function is_iterable;
use function is_numeric;
use function is_object;
use function is_scalar;
use function is_string;
use function sprintf;
use PHPUnit\Framework\NativeType;

/**
@no-named-arguments
*/
final class IsType extends Constraint
{
private readonly NativeType $type;

public function __construct(NativeType $type)
{
$this->type = $type;
}




public function toString(): string
{
return sprintf(
'is of type %s',
$this->type->value,
);
}





protected function matches(mixed $other): bool
{
switch ($this->type) {
case NativeType::Numeric:
return is_numeric($other);

case NativeType::Int:
return is_int($other);

case NativeType::Float:
return is_float($other);

case NativeType::String:
return is_string($other);

case NativeType::Bool:
return is_bool($other);

case NativeType::Null:
return null === $other;

case NativeType::Array:
return is_array($other);

case NativeType::Object:
return is_object($other);

case NativeType::Resource:
$type = gettype($other);

return $type === 'resource' || $type === 'resource (closed)';

case NativeType::ClosedResource:
return gettype($other) === 'resource (closed)';

case NativeType::Scalar:
return is_scalar($other);

case NativeType::Callable:
return is_callable($other);

case NativeType::Iterable:
return is_iterable($other);

default:
return false;
}
}
}
