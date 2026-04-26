<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject;

use PHPUnit\Framework\MockObject\Rule\InvocationOrder;

/**
@no-named-arguments


*/
trait MockObjectApi
{
public function __phpunit_hasInvocationCountRule(): bool
{
return $this->__phpunit_getInvocationHandler()->hasInvocationCountRule();
}

public function __phpunit_hasParametersRule(): bool
{
return $this->__phpunit_getInvocationHandler()->hasParametersRule();
}

public function __phpunit_verify(bool $unsetInvocationMocker = true): void
{
$this->__phpunit_getInvocationHandler()->verify();

if ($unsetInvocationMocker) {
$this->__phpunit_unsetInvocationMocker();
}
}

abstract public function __phpunit_state(): TestDoubleState;

abstract public function __phpunit_getInvocationHandler(): InvocationHandler;

abstract public function __phpunit_unsetInvocationMocker(): void;

public function expects(InvocationOrder $matcher): InvocationStubber
{
return $this->__phpunit_getInvocationHandler()->expects($matcher);
}
}
