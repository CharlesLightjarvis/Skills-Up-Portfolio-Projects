<?php declare(strict_types=1);








namespace PHPUnit\Framework\Attributes;

use Attribute;

/**
@immutable
@no-named-arguments

*/
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class DataProviderExternal
{



private string $className;




private string $methodName;
private bool $validateArgumentCount;





public function __construct(string $className, string $methodName, bool $validateArgumentCount = true)
{
$this->className = $className;
$this->methodName = $methodName;
$this->validateArgumentCount = $validateArgumentCount;
}




public function className(): string
{
return $this->className;
}




public function methodName(): string
{
return $this->methodName;
}

public function validateArgumentCount(): bool
{
return $this->validateArgumentCount;
}
}
