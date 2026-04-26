<?php declare(strict_types=1);








namespace PHPUnit\Metadata;

/**
@immutable
@no-named-arguments

*/
final readonly class WithEnvironmentVariable extends Metadata
{



private string $environmentVariableName;
private null|string $value;




protected function __construct(int $level, string $environmentVariableName, null|string $value)
{
parent::__construct($level);

$this->environmentVariableName = $environmentVariableName;
$this->value = $value;
}

public function isWithEnvironmentVariable(): true
{
return true;
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
