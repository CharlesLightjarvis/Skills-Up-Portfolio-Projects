<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject;

use function assert;
use PHPUnit\Framework\MockObject\Generator\ClassIsEnumerationException;
use PHPUnit\Framework\MockObject\Generator\ClassIsFinalException;
use PHPUnit\Framework\MockObject\Generator\DuplicateMethodException;
use PHPUnit\Framework\MockObject\Generator\InvalidMethodNameException;
use PHPUnit\Framework\MockObject\Generator\NameAlreadyInUseException;
use PHPUnit\Framework\MockObject\Generator\ReflectionException;
use PHPUnit\Framework\MockObject\Generator\RuntimeException;
use PHPUnit\Framework\MockObject\Generator\UnknownTypeException;

/**
@template
@no-named-arguments

*/
final class TestStubBuilder extends TestDoubleBuilder
{



private ?string $stubClassName = null;















public function getStub(): Stub
{
$object = $this->getTestDouble($this->stubClassName, false);

assert($object instanceof $this->type);
assert($object instanceof Stub);
assert(!$object instanceof MockObject);

return $object;
}








public function setStubClassName(string $name): self
{
$this->stubClassName = $name;

return $this;
}
}
