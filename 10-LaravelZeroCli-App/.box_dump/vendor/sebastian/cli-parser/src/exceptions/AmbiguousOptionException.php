<?php declare(strict_types=1);








namespace SebastianBergmann\CliParser;

use function implode;
use function sprintf;
use RuntimeException;

final class AmbiguousOptionException extends RuntimeException implements Exception
{



public function __construct(string $option, array $candiates)
{
parent::__construct(
sprintf(
'Option "%s" is ambiguous. Similar options are: %s',
$option,
implode(', ', $candiates),
),
);
}
}
