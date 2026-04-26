<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\PseudoTypes;

use phpDocumentor\Reflection\PseudoType;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Resource_;

/**
@psalm-immutable


*/
final class ClosedResource extends Resource_ implements PseudoType
{
public function underlyingType(): Type
{
return new Resource_();
}

public function __toString(): string
{
return 'closed-resource';
}
}
