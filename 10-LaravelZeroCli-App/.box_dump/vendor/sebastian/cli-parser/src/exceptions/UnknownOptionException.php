<?php declare(strict_types=1);








namespace SebastianBergmann\CliParser;

use function implode;
use function sprintf;
use RuntimeException;

final class UnknownOptionException extends RuntimeException implements Exception
{



public function __construct(string $option, array $similarOptions)
{
$message = sprintf(
'Unknown option "%s"',
$option,
);

if ($similarOptions !== []) {
$message = sprintf(
'Unknown option "%s". Most similar options are %s',
$option,
implode(', ', $similarOptions),
);
}

parent::__construct($message);
}
}
