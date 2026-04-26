<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Report\Xml;

use function sprintf;
use SebastianBergmann\CodeCoverage\Util\Percentage;
use XMLWriter;




final readonly class Totals
{
private XMLWriter $xmlWriter;

public function __construct(XMLWriter $xmlWriter)
{
$this->xmlWriter = $xmlWriter;
}

public function setNumLines(int $loc, int $cloc, int $ncloc, int $executable, int $executed): void
{
$this->xmlWriter->startElement('lines');
$this->xmlWriter->writeAttribute('total', (string) $loc);
$this->xmlWriter->writeAttribute('comments', (string) $cloc);
$this->xmlWriter->writeAttribute('code', (string) $ncloc);
$this->xmlWriter->writeAttribute('executable', (string) $executable);
$this->xmlWriter->writeAttribute('executed', (string) $executed);
$this->xmlWriter->writeAttribute(
'percent',
$executable === 0 ? '0' : sprintf('%01.2F', Percentage::fromFractionAndTotal($executed, $executable)->asFloat()),
);
$this->xmlWriter->endElement();
}

public function setNumClasses(int $count, int $tested): void
{
$this->xmlWriter->startElement('classes');
$this->xmlWriter->writeAttribute('count', (string) $count);
$this->xmlWriter->writeAttribute('tested', (string) $tested);
$this->xmlWriter->writeAttribute(
'percent',
$count === 0 ? '0' : sprintf('%01.2F', Percentage::fromFractionAndTotal($tested, $count)->asFloat()),
);
$this->xmlWriter->endElement();
}

public function setNumTraits(int $count, int $tested): void
{
$this->xmlWriter->startElement('traits');
$this->xmlWriter->writeAttribute('count', (string) $count);
$this->xmlWriter->writeAttribute('tested', (string) $tested);
$this->xmlWriter->writeAttribute(
'percent',
$count === 0 ? '0' : sprintf('%01.2F', Percentage::fromFractionAndTotal($tested, $count)->asFloat()),
);
$this->xmlWriter->endElement();
}

public function setNumMethods(int $count, int $tested): void
{
$this->xmlWriter->startElement('methods');
$this->xmlWriter->writeAttribute('count', (string) $count);
$this->xmlWriter->writeAttribute('tested', (string) $tested);
$this->xmlWriter->writeAttribute(
'percent',
$count === 0 ? '0' : sprintf('%01.2F', Percentage::fromFractionAndTotal($tested, $count)->asFloat()),
);
$this->xmlWriter->endElement();
}

public function setNumFunctions(int $count, int $tested): void
{
$this->xmlWriter->startElement('functions');
$this->xmlWriter->writeAttribute('count', (string) $count);
$this->xmlWriter->writeAttribute('tested', (string) $tested);
$this->xmlWriter->writeAttribute(
'percent',
$count === 0 ? '0' : sprintf('%01.2F', Percentage::fromFractionAndTotal($tested, $count)->asFloat()),
);
$this->xmlWriter->endElement();
}

public function getWriter(): XMLWriter
{
return $this->xmlWriter;
}
}
