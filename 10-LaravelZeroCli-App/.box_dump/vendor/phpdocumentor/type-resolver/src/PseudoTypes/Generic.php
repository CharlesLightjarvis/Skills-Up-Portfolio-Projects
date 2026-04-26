<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\PseudoTypes;

use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Object_;

use function implode;

/**
@psalm-immutable


*/
final class Generic extends Object_
{

private $types;




public function __construct(?Fqsen $fqsen, array $types)
{
parent::__construct($fqsen);

$this->types = $types;
}




public function getTypes(): array
{
return $this->types;
}

public function __toString(): string
{
$objectType = (string) ($this->fqsen ?? 'object');

return $objectType . '<' . implode(', ', $this->types) . '>';
}
}
