<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\PseudoTypes;

use phpDocumentor\Reflection\PseudoType;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Integer;

/**
@psalm-immutable


*/
final class NonEmptyList extends Array_ implements PseudoType
{
public function underlyingType(): Type
{
return new Array_($this->valueType, $this->keyType);
}

public function __construct(?Type $valueType = null)
{
parent::__construct($valueType, new Integer());
}




public function __toString(): string
{
if ($this->valueType === null) {
return 'non-empty-list';
}

return 'non-empty-list<' . $this->valueType . '>';
}
}
