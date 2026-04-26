<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Driver;

use function sprintf;
use RuntimeException;
use SebastianBergmann\CodeCoverage\Exception;

final class XdebugVersionNotSupportedException extends RuntimeException implements Exception
{



public function __construct(string $version)
{
parent::__construct(
sprintf(
'Version %s of the Xdebug extension is not supported',
$version,
),
);
}
}
