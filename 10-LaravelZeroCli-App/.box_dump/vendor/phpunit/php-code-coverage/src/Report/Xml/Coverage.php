<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Report\Xml;

use XMLWriter;




final class Coverage
{
private readonly XMLWriter $xmlWriter;
private readonly string $line;

public function __construct(
XMLWriter $xmlWriter,
string $line
) {
$this->xmlWriter = $xmlWriter;
$this->line = $line;
}

public function finalize(array $tests): void
{
$writer = $this->xmlWriter;
$writer->startElement('line');
$writer->writeAttribute('nr', $this->line);

foreach ($tests as $test) {
$writer->startElement('covered');
$writer->writeAttribute('by', $test);
$writer->endElement();
}
$writer->endElement();
}
}
