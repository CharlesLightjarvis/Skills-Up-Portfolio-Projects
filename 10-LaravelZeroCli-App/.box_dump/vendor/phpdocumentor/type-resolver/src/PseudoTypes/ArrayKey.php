<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\PseudoTypes;

use phpDocumentor\Reflection\PseudoType;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\AggregatedType;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\String_;

/**
@psalm-immutable


*/
class ArrayKey extends AggregatedType implements PseudoType
{
public function __construct()
{
parent::__construct([new String_(), new Integer()], '|');
}

public function underlyingType(): Type
{
return new Compound([new String_(), new Integer()]);
}

public function __toString(): string
{
return 'array-key';
}
}
