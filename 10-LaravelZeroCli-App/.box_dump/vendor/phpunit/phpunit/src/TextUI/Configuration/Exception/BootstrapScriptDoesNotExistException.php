<?php declare(strict_types=1);








namespace PHPUnit\TextUI\Configuration;

use function sprintf;
use RuntimeException;

/**
@no-named-arguments


*/
final class BootstrapScriptDoesNotExistException extends RuntimeException implements Exception
{
public function __construct(string $filename)
{
parent::__construct(
sprintf(
'Cannot open bootstrap script "%s"',
$filename,
),
);
}
}
