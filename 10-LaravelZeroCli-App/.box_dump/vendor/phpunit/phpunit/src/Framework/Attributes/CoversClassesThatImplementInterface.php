<?php declare(strict_types=1);








namespace PHPUnit\Framework\Attributes;

use Attribute;

/**
@immutable
@no-named-arguments

*/
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class CoversClassesThatImplementInterface
{



private string $interfaceName;




public function __construct(string $interfaceName)
{
$this->interfaceName = $interfaceName;
}




public function interfaceName(): string
{
return $this->interfaceName;
}
}
