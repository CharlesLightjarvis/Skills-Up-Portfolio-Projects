<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\Types;

use function preg_match;
use function substr;

/**
@psalm-immutable








*/
class Array_ extends AbstractList
{
public function __toString(): string
{
if ($this->valueType === null) {
return 'array';
}

$valueTypeString = (string) $this->valueType;

if ($this->keyType) {
return 'array<' . $this->keyType . ', ' . $valueTypeString . '>';
}

if (!preg_match('/[^\w\\\\]/', $valueTypeString) || substr($valueTypeString, -2, 2) === '[]') {
return $valueTypeString . '[]';
}

return 'array<' . $valueTypeString . '>';
}
}
