<?php










declare(strict_types=1);

namespace phpDocumentor\Reflection\PseudoTypes;

/**
@psalm-immutable


*/
final class PrivatePropertiesOf extends PropertiesOf
{
public function __toString(): string
{
return 'private-properties-of<' . $this->type . '>';
}
}
