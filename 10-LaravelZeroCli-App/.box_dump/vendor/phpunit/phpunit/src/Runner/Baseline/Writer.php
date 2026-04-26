<?php declare(strict_types=1);








namespace PHPUnit\Runner\Baseline;

use function dirname;
use function file_put_contents;
use function is_dir;
use function realpath;
use function sprintf;
use XMLWriter;

/**
@no-named-arguments


*/
final readonly class Writer
{





public function write(string $baselineFile, Baseline $baseline): void
{
$normalizedBaselineFile = realpath(dirname($baselineFile));

if ($normalizedBaselineFile === false || !is_dir($normalizedBaselineFile)) {
throw new CannotWriteBaselineException(sprintf('Cannot write baseline to "%s".', $baselineFile));
}

$pathCalculator = new RelativePathCalculator($normalizedBaselineFile);

$writer = new XMLWriter;

$writer->openMemory();
$writer->setIndent(true);
$writer->startDocument();

$writer->startElement('files');
$writer->writeAttribute('version', (string) Baseline::VERSION);

foreach ($baseline->groupedByFileAndLine() as $file => $lines) {
$writer->startElement('file');
$writer->writeAttribute('path', $pathCalculator->calculate($file));

foreach ($lines as $line => $issues) {
$writer->startElement('line');
$writer->writeAttribute('number', (string) $line);
$writer->writeAttribute('hash', $issues[0]->hash());

foreach ($issues as $issue) {
$writer->startElement('issue');
$writer->writeCdata($issue->description());
$writer->endElement();
}

$writer->endElement();
}

$writer->endElement();
}

$writer->endElement();

file_put_contents($baselineFile, $writer->outputMemory());
}
}
