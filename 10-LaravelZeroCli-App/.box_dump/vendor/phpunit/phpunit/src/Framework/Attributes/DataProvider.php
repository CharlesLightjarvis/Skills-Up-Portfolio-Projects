<?php declare(strict_types=1);








namespace PHPUnit\Framework\Attributes;

use Attribute;

/**
@immutable
@no-named-arguments

*/
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class DataProvider
{



private string $methodName;
private bool $validateArgumentCount;




public function __construct(string $methodName, bool $validateArgumentCount = true)
{
$this->methodName = $methodName;
$this->validateArgumentCount = $validateArgumentCount;
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
