<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\Rule\AnyInvokedCount;
use PHPUnit\Framework\MockObject\Runtime\PropertyHook;

/**
@no-named-arguments


*/
trait Method
{
abstract public function __phpunit_getInvocationHandler(): InvocationHandler;

public function method(Constraint|PropertyHook|string $constraint): InvocationStubber
{
return $this
->__phpunit_getInvocationHandler()
->expects(new AnyInvokedCount)
->method($constraint);
}
}
