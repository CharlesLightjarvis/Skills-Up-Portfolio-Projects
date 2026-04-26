<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\PseudoTypes;

use phpDocumentor\Reflection\PseudoType;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\String_;

/**
@psalm-immutable


*/
final class Scalar implements PseudoType
{
public function underlyingType(): Type
{
return new Compound([new String_(), new Integer(), new Float_(), new Boolean()]);
}




public function __toString(): string
{
return 'scalar';
}
}
