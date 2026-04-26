<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject;

use function array_map;
use function implode;
use function sprintf;
use function str_starts_with;
use function strtolower;
use function substr;
use PHPUnit\Framework\SelfDescribing;
use PHPUnit\Util\Exporter;

/**
@no-named-arguments


*/
final readonly class Invocation implements SelfDescribing
{



private string $className;




private string $methodName;




private array $parameters;
private string $returnType;
private bool $isReturnTypeNullable;
private MockObjectInternal|StubInternal $object;






public function __construct(string $className, string $methodName, array $parameters, string $returnType, MockObjectInternal|StubInternal $object)
{
$this->className = $className;
$this->methodName = $methodName;
$this->parameters = $parameters;
$this->object = $object;

if (strtolower($methodName) === '__tostring') {
$returnType = 'string';
}

if (str_starts_with($returnType, '?')) {
$returnType = substr($returnType, 1);
$this->isReturnTypeNullable = true;
} else {
$this->isReturnTypeNullable = false;
}

$this->returnType = $returnType;
}




public function className(): string
{
return $this->className;
}




public function methodName(): string
{
return $this->methodName;
}




public function parameters(): array
{
return $this->parameters;
}




public function generateReturnValue(): mixed
{
if ($this->returnType === 'never') {
throw new NeverReturningMethodException(
$this->className,
$this->methodName,
);
}

if ($this->isReturnTypeNullable) {
return null;
}

return (new ReturnValueGenerator)->generate(
$this->className,
$this->methodName,
$this->object,
$this->returnType,
);
}

public function toString(): string
{
return sprintf(
'%s::%s(%s)%s',
$this->className,
$this->methodName,
implode(
', ',
array_map(
[Exporter::class, 'shortenedExport'],
$this->parameters,
),
),
$this->returnType !== '' ? sprintf(': %s', $this->returnType) : '',
);
}

public function object(): MockObjectInternal|StubInternal
{
return $this->object;
}
}
