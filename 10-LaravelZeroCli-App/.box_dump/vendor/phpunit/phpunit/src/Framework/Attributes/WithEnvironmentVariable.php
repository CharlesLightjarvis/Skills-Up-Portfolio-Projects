<?php declare(strict_types=1);








namespace PHPUnit\Framework\Attributes;

use Attribute;

/**
@immutable
@no-named-arguments

*/
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class WithEnvironmentVariable
{



private string $environmentVariableName;
private null|string $value;




public function __construct(string $environmentVariableName, null|string $value = null)
{
$this->environmentVariableName = $environmentVariableName;
$this->value = $value;
}




public function environmentVariableName(): string
{
return $this->environmentVariableName;
}

public function value(): null|string
{
return $this->value;
}
}
