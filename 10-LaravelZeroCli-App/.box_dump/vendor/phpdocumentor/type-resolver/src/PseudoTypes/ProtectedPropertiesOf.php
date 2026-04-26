<?php










declare(strict_types=1);

namespace phpDocumentor\Reflection\PseudoTypes;

/**
@psalm-immutable


*/
final class ProtectedPropertiesOf extends PropertiesOf
{
public function __toString(): string
{
return 'protected-properties-of<' . $this->type . '>';
}
}
