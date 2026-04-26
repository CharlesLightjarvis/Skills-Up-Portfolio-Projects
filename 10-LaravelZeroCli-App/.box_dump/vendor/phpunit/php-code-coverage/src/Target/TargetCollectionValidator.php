<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

use function implode;

/**
@no-named-arguments


*/
final readonly class TargetCollectionValidator
{
public function validate(Mapper $mapper, TargetCollection $targets): ValidationResult
{
$errors = [];

foreach ($targets as $target) {
try {
$mapper->mapTarget($target);
} catch (InvalidCodeCoverageTargetException $e) {
$errors[] = $e->getMessage();
}
}

if ($errors === []) {
return ValidationResult::success();
}

return ValidationResult::failure(implode("\n", $errors));
}
}
