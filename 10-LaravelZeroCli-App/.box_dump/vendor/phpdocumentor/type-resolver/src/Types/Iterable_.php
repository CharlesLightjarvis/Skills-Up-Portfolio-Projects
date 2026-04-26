<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\Types;

/**
@psalm-immutable


*/
final class Iterable_ extends AbstractList
{



public function __toString(): string
{
if ($this->valueType === null) {
return 'iterable';
}

if ($this->keyType) {
return 'iterable<' . $this->keyType . ', ' . $this->valueType . '>';
}

return 'iterable<' . $this->valueType . '>';
}
}
