<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Report;

use const PHP_EOL;
use function serialize;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Util\Filesystem;
use SebastianBergmann\CodeCoverage\WriteOperationFailedException;

final class PHP
{





public function process(CodeCoverage $coverage, ?string $target = null): string
{
$coverage->clearCache();

$buffer = "<?php
return \unserialize(<<<'END_OF_COVERAGE_SERIALIZATION'" . PHP_EOL . serialize($coverage) . PHP_EOL . 'END_OF_COVERAGE_SERIALIZATION' . PHP_EOL . ');';

if ($target !== null) {
Filesystem::write($target, $buffer);
}

return $buffer;
}
}
