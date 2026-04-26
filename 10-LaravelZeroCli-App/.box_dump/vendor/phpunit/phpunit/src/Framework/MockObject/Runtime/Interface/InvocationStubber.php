<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\Runtime\PropertyHook;
use PHPUnit\Framework\MockObject\Stub\Stub;
use Throwable;

interface InvocationStubber
{
/**
@no-named-arguments




*/
public function method(Constraint|PropertyHook|string $constraint): self;

/**
@no-named-arguments




*/
public function id(string $id): self;

/**
@no-named-arguments




*/
public function after(string $id): self;




public function with(mixed ...$arguments): self;

/**
@no-named-arguments


*/
public function withAnyParameters(): self;

/**
@no-named-arguments


*/
public function will(Stub $stub): self;

/**
@no-named-arguments


*/
public function willReturn(mixed $value, mixed ...$nextValues): self;

/**
@no-named-arguments


*/
public function willReturnReference(mixed &$reference): self;

/**
@no-named-arguments




*/
public function willReturnMap(array $valueMap): self;

/**
@no-named-arguments


*/
public function willReturnArgument(int $argumentIndex): self;

/**
@no-named-arguments


*/
public function willReturnCallback(callable $callback): self;

/**
@no-named-arguments


*/
public function willReturnSelf(): self;

/**
@no-named-arguments


*/
public function willReturnOnConsecutiveCalls(mixed ...$values): self;

/**
@no-named-arguments


*/
public function willThrowException(Throwable $exception): self;
}
