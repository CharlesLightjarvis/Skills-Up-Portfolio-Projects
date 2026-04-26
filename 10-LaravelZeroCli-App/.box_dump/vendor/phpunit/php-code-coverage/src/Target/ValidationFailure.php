<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

/**
@immutable
@no-named-arguments

*/
final readonly class ValidationFailure extends ValidationResult
{



private string $message;

/**
@noinspection


*/
protected function __construct(string $message)
{
$this->message = $message;
}

public function isFailure(): true
{
return true;
}




public function message(): string
{
return $this->message;
}
}
