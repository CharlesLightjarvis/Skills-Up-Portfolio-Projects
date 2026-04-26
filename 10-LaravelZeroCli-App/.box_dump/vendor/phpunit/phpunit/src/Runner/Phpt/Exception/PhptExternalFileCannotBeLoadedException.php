<?php declare(strict_types=1);








namespace PHPUnit\Runner\Phpt;

use function sprintf;
use PHPUnit\Runner\Exception as RunnerException;
use RuntimeException;

/**
@no-named-arguments


*/
final class PhptExternalFileCannotBeLoadedException extends RuntimeException implements RunnerException
{
public function __construct(string $section, string $file)
{
parent::__construct(
sprintf(
'Could not load --%s-- %s for PHPT file',
$section . '_EXTERNAL',
$file,
),
);
}
}
