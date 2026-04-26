<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

use function sprintf;
use RuntimeException;
use SebastianBergmann\CodeCoverage\Exception;

final class InvalidCodeCoverageTargetException extends RuntimeException implements Exception
{
public function __construct(Target $target)
{
parent::__construct(
sprintf(
'%s is not a valid target for code coverage',
$target->description(),
),
);
}
}
