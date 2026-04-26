<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject;

/**
@no-named-arguments


*/
interface MockObjectInternal extends MockObject, StubInternal
{
public function __phpunit_hasInvocationCountRule(): bool;

public function __phpunit_hasParametersRule(): bool;

public function __phpunit_verify(bool $unsetInvocationMocker = true): void;
}
