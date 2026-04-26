<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage;

use function sprintf;
use RuntimeException;

final class WriteOperationFailedException extends RuntimeException implements Exception
{
public function __construct(string $path)
{
parent::__construct(sprintf('Cannot write to "%s"', $path));
}
}
