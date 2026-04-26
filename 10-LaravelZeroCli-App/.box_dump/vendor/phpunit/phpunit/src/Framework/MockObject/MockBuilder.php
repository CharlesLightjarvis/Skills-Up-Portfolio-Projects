<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject;

use function assert;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\Generator\ClassIsEnumerationException;
use PHPUnit\Framework\MockObject\Generator\ClassIsFinalException;
use PHPUnit\Framework\MockObject\Generator\DuplicateMethodException;
use PHPUnit\Framework\MockObject\Generator\InvalidMethodNameException;
use PHPUnit\Framework\MockObject\Generator\NameAlreadyInUseException;
use PHPUnit\Framework\MockObject\Generator\ReflectionException;
use PHPUnit\Framework\MockObject\Generator\RuntimeException;
use PHPUnit\Framework\MockObject\Generator\UnknownTypeException;
use PHPUnit\Framework\TestCase;

/**
@template
@no-named-arguments

*/
final class MockBuilder extends TestDoubleBuilder
{
private readonly TestCase $testCase;




private ?string $mockClassName = null;




public function __construct(TestCase $testCase, string $type)
{
parent::__construct($type);

$this->testCase = $testCase;
}
















public function getMock(): MockObject
{
$object = $this->getTestDouble($this->mockClassName, true);

assert($object instanceof $this->type);
assert($object instanceof MockObject);

$this->testCase->registerMockObject($this->type, $object);

return $object;
}








public function setMockClassName(string $name): self
{
$this->mockClassName = $name;

return $this;
}
}
