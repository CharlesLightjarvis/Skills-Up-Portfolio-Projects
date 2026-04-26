<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Report\Xml;

use function sprintf;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use XMLWriter;

/**
@phpstan-import-type


*/
final readonly class Tests
{
private readonly XMLWriter $xmlWriter;

public function __construct(XMLWriter $xmlWriter)
{
$this->xmlWriter = $xmlWriter;
}




public function addTest(string $test, array $result): void
{
$this->xmlWriter->startElement('test');

$this->xmlWriter->writeAttribute('name', $test);
$this->xmlWriter->writeAttribute('size', $result['size']);
$this->xmlWriter->writeAttribute('status', $result['status']);
$this->xmlWriter->writeAttribute('time', sprintf('%F', $result['time']));

$this->xmlWriter->endElement();
}
}
