<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject;

use function array_merge;
use PHPUnit\Framework\MockObject\Generator\Generator;
use PHPUnit\Framework\MockObject\Generator\ReflectionException;
use ReflectionClass;

/**
@no-named-arguments
*/
abstract class TestDoubleBuilder
{



protected readonly string $type;




protected array $methods = [];
protected bool $emptyMethodsArray = false;




protected array $constructorArgs = [];
protected bool $originalConstructor = true;
protected bool $originalClone = true;
protected bool $returnValueGeneration = true;




public function __construct(string $type)
{
$this->type = $type;
}











public function onlyMethods(array $methods): static
{
if ($methods === []) {
$this->emptyMethodsArray = true;

return $this;
}

try {
$reflector = new ReflectionClass($this->type);


/**
@phpstan-ignore */
} catch (\ReflectionException $e) {
throw new ReflectionException(
$e->getMessage(),
$e->getCode(),
$e,
);

}

foreach ($methods as $method) {
if (!$reflector->hasMethod($method)) {
throw new CannotUseOnlyMethodsException($this->type, $method);
}
}

$this->methods = array_merge($this->methods, $methods);

return $this;
}








public function setConstructorArgs(array $arguments): static
{
$this->constructorArgs = $arguments;

return $this;
}






public function disableOriginalConstructor(): static
{
$this->originalConstructor = false;

return $this;
}






public function enableOriginalConstructor(): static
{
$this->originalConstructor = true;

return $this;
}






public function disableOriginalClone(): static
{
$this->originalClone = false;

return $this;
}






public function enableOriginalClone(): static
{
$this->originalClone = true;

return $this;
}




public function enableAutoReturnValueGeneration(): static
{
$this->returnValueGeneration = true;

return $this;
}




public function disableAutoReturnValueGeneration(): static
{
$this->returnValueGeneration = false;

return $this;
}

protected function getTestDouble(?string $testDoubleClassName, bool $mockObject): MockObject|Stub
{
return (new Generator)->testDouble(
$this->type,
$mockObject,
!$this->emptyMethodsArray ? $this->methods : null,
$this->constructorArgs,
$testDoubleClassName ?? '',
$this->originalConstructor,
$this->originalClone,
$this->returnValueGeneration,
);
}
}
