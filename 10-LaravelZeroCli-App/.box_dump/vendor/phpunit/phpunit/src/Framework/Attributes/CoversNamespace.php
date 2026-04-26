<?php declare(strict_types=1);








namespace PHPUnit\Framework\Attributes;

use Attribute;

/**
@immutable
@no-named-arguments

*/
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class CoversNamespace
{



private string $namespace;




public function __construct(string $namespace)
{
$this->namespace = $namespace;
}




public function namespace(): string
{
return $this->namespace;
}
}
