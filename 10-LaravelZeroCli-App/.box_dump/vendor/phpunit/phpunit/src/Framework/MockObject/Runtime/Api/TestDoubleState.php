<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject;

/**
@no-named-arguments


*/
final class TestDoubleState
{



private readonly array $configurableMethods;
private readonly bool $generateReturnValues;
private ?InvocationHandler $invocationHandler = null;
private readonly bool $isMockObject;




public function __construct(array $configurableMethods, bool $generateReturnValues, bool $isMockObject = false)
{
$this->configurableMethods = $configurableMethods;
$this->generateReturnValues = $generateReturnValues;
$this->isMockObject = $isMockObject;
}

public function invocationHandler(): InvocationHandler
{
if ($this->invocationHandler !== null) {
return $this->invocationHandler;
}

$this->invocationHandler = new InvocationHandler(
$this->configurableMethods,
$this->generateReturnValues,
$this->isMockObject,
);

return $this->invocationHandler;
}

public function cloneInvocationHandler(): void
{
if ($this->invocationHandler === null) {
return;
}

$this->invocationHandler = clone $this->invocationHandler;
}

public function unsetInvocationHandler(): void
{
$this->invocationHandler = null;
}




public function configurableMethods(): array
{
return $this->configurableMethods;
}

public function generateReturnValues(): bool
{
return $this->generateReturnValues;
}
}
