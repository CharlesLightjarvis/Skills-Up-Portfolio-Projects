<?php declare(strict_types=1);








namespace PHPUnit\Runner\Phpt;

use function sprintf;
use PHPUnit\Runner\Exception as RunnerException;
use RuntimeException;

/**
@no-named-arguments


*/
final class UnsupportedPhptSectionException extends RuntimeException implements RunnerException
{
public function __construct(string $section)
{
parent::__construct(
sprintf(
'PHPUnit does not support PHPT --%s-- sections',
$section,
),
);
}
}
