<?php










declare(strict_types=1);

namespace phpDocumentor\Reflection\PseudoTypes;

/**
@psalm-immutable


*/
final class PublicPropertiesOf extends PropertiesOf
{
public function __toString(): string
{
return 'public-properties-of<' . $this->type . '>';
}
}
