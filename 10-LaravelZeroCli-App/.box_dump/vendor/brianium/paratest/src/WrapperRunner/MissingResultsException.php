<?php

declare(strict_types=1);

namespace ParaTest\WrapperRunner;

use RuntimeException;

use function implode;
use function sprintf;


final class MissingResultsException extends RuntimeException
{




public static function create(array $missingFiles, string $fileType): self
{
$fileTypeLabel = $fileType === 'test_result' ? 'test result' : 'coverage';

$message = sprintf(
'One or more workers failed to generate %s files, likely due to unexpected process termination (e.g., out of memory). ' .
'Missing %s files: %s',
$fileTypeLabel,
$fileTypeLabel,
implode(', ', $missingFiles),
);

return new self($message);
}
}
