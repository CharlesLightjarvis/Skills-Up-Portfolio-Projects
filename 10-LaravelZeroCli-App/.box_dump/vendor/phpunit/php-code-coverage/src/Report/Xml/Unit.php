<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Report\Xml;

use XMLWriter;




final readonly class Unit
{
private XMLWriter $xmlWriter;

public function __construct(
XMLWriter $xmlWriter,
string $name,
string $namespace,
int $start,
int $executable,
int $executed,
float $crap
) {
$this->xmlWriter = $xmlWriter;

$this->xmlWriter->writeAttribute('name', $name);
$this->xmlWriter->writeAttribute('start', (string) $start);
$this->xmlWriter->writeAttribute('executable', (string) $executable);
$this->xmlWriter->writeAttribute('executed', (string) $executed);
$this->xmlWriter->writeAttribute('crap', (string) $crap);

$this->xmlWriter->startElement('namespace');
$this->xmlWriter->writeAttribute('name', $namespace);
$this->xmlWriter->endElement();
}

public function addMethod(
string $name,
string $signature,
string $start,
?string $end,
string $executable,
string $executed,
string $coverage,
string $crap
): void {
new Method(
$this->xmlWriter,
$name,
$signature,
$start,
$end,
$executable,
$executed,
$coverage,
$crap,
);
}
}
