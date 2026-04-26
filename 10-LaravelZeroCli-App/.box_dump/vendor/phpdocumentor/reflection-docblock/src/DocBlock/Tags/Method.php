<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\DocBlock\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\Exception\CannotCreateTag;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Void_;
use Webmozart\Assert\Assert;

use function implode;




final class Method extends BaseTag
{
protected string $name = 'method';

private string $methodName;

private bool $isStatic;

private Type $returnType;

private bool $returnsReference;


private array $parameters;




public function __construct(
string $methodName,
array $parameters = [],
?Type $returnType = null,
bool $static = false,
?Description $description = null,
bool $returnsReference = false
) {
Assert::stringNotEmpty($methodName);

if ($returnType === null) {
$returnType = new Void_();
}

$this->methodName = $methodName;
$this->returnType = $returnType;
$this->isStatic = $static;
$this->description = $description;
$this->returnsReference = $returnsReference;
$this->parameters = $parameters;
}




public function getMethodName(): string
{
return $this->methodName;
}


public function getParameters(): array
{
return $this->parameters;
}






public function isStatic(): bool
{
return $this->isStatic;
}

public function getReturnType(): Type
{
return $this->returnType;
}

public function returnsReference(): bool
{
return $this->returnsReference;
}

public function __toString(): string
{
$arguments = [];
foreach ($this->parameters as $parameter) {
$arguments[] = (string) $parameter;
}

$argumentStr = '(' . implode(', ', $arguments) . ')';

if ($this->description) {
$description = $this->description->render();
} else {
$description = '';
}

$static = $this->isStatic ? 'static' : '';

$returnType = (string) $this->returnType;

$methodName = $this->methodName;

$reference = $this->returnsReference ? '&' : '';

return $static
. ($returnType !== '' ? ($static !== '' ? ' ' : '') . $returnType : '')
. ($methodName !== '' ? ($static !== '' || $returnType !== '' ? ' ' : '') . $reference . $methodName : '')
. $argumentStr
. ($description !== '' ? ' ' . $description : '');
}

public static function create(string $body): void
{
throw new CannotCreateTag('Method tag cannot be created');
}
}
