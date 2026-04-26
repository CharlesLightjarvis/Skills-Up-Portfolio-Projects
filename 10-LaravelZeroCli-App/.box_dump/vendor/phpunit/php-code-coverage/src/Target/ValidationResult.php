<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

/**
@immutable
@no-named-arguments

*/
abstract readonly class ValidationResult
{
public static function success(): ValidationSuccess
{
return new ValidationSuccess;
}




public static function failure(string $message): ValidationFailure
{
return new ValidationFailure($message);
}

/**
@phpstan-assert-if-true
*/
public function isSuccess(): bool
{
return false;
}

/**
@phpstan-assert-if-true
*/
public function isFailure(): bool
{
return false;
}
}
