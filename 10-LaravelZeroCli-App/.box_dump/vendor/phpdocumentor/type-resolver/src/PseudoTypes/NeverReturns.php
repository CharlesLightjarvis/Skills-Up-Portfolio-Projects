<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\PseudoTypes;

use phpDocumentor\Reflection\PseudoType;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Never_;

/**
@psalm-immutable


*/
final class NeverReturns extends Never_ implements PseudoType
{
public function underlyingType(): Type
{
return new Never_();
}

public function __toString(): string
{
return 'never-returns';
}
}
