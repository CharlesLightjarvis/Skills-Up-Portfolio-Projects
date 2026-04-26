<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\Types;

use phpDocumentor\Reflection\Type;

use function implode;

/**
@psalm-immutable


*/
final class Callable_ implements Type
{

private $identifier;

private $returnType;

private $parameters;




public function __construct(
string $identifier = 'callable',
array $parameters = [],
?Type $returnType = null
) {
$this->identifier = $identifier;
$this->parameters = $parameters;
$this->returnType = $returnType;
}

public function getIdentifier(): string
{
return $this->identifier;
}


public function getParameters(): array
{
return $this->parameters;
}

public function getReturnType(): ?Type
{
return $this->returnType;
}




public function __toString(): string
{
if (!$this->parameters && $this->returnType === null) {
return $this->identifier;
}

if ($this->returnType instanceof self) {
$returnType = '(' . (string) $this->returnType . ')';
} else {
$returnType = (string) $this->returnType;
}

return $this->identifier . '(' . implode(', ', $this->parameters) . '): ' . $returnType;
}
}
