<?php declare(strict_types=1);








namespace PHPUnit\Metadata;

/**
@immutable
@no-named-arguments

*/
final readonly class DataProvider extends Metadata
{



private string $className;




private string $methodName;
private bool $validateArgumentCount;






protected function __construct(int $level, string $className, string $methodName, bool $validateArgumentCount)
{
parent::__construct($level);

$this->className = $className;
$this->methodName = $methodName;
$this->validateArgumentCount = $validateArgumentCount;
}

public function isDataProvider(): true
{
return true;
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
