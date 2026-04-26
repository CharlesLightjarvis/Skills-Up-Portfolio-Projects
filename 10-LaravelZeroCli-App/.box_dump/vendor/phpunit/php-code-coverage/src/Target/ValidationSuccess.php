<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

/**
@immutable
@no-named-arguments

*/
final readonly class ValidationSuccess extends ValidationResult
{
public function isSuccess(): true
{
return true;
}
}
