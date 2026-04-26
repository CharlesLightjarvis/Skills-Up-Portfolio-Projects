<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\Runtime\PropertyHook;

/**
@no-named-arguments
*/
interface Stub
{
public function method(Constraint|PropertyHook|string $constraint): InvocationStubber;
}
