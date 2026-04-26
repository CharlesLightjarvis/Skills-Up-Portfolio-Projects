<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\PseudoTypes;

use phpDocumentor\Reflection\PseudoType;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\String_;

/**
@psalm-immutable


*/
final class EnumString extends String_ implements PseudoType
{

private $genericType;

public function __construct(?Type $genericType = null)
{
$this->genericType = $genericType;
}

public function underlyingType(): Type
{
return new String_();
}

public function getGenericType(): ?Type
{
return $this->genericType;
}




public function __toString(): string
{
if ($this->genericType === null) {
return 'enum-string';
}

return 'enum-string<' . (string) $this->genericType . '>';
}
}
