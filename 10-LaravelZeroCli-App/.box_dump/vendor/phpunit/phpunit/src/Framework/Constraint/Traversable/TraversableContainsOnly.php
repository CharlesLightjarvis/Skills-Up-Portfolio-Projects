<?php declare(strict_types=1);








namespace PHPUnit\Framework\Constraint;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\NativeType;

/**
@no-named-arguments
*/
final class TraversableContainsOnly extends Constraint
{
private readonly Constraint $constraint;
private readonly string $type;

public static function forNativeType(NativeType $type): self
{
return new self(new IsType($type), $type->value);
}




public static function forClassOrInterface(string $type): self
{
return new self(new IsInstanceOf($type), $type);
}

private function __construct(IsInstanceOf|IsType $constraint, string $type)
{
$this->constraint = $constraint;
$this->type = $type;
}













public function evaluate(mixed $other, string $description = '', bool $returnResult = false): bool
{
$success = true;

foreach ($other as $item) {
if (!$this->constraint->evaluate($item, '', true)) {
$success = false;

break;
}
}

if (!$success && !$returnResult) {
$this->fail($other, $description);
}

return $success;
}




public function toString(): string
{
return 'contains only values of type "' . $this->type . '"';
}
}
