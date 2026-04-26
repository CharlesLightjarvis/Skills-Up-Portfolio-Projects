<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Report\Xml;

use XMLWriter;




final readonly class Method
{
private XMLWriter $xmlWriter;

public function __construct(
XMLWriter $xmlWriter,
string $name,
string $signature,
string $start,
?string $end,
string $executable,
string $executed,
string $coverage,
string $crap
) {
$this->xmlWriter = $xmlWriter;

$this->xmlWriter->writeAttribute('name', $name);
$this->xmlWriter->writeAttribute('signature', $signature);

$this->xmlWriter->writeAttribute('start', $start);

if ($end !== null) {
$this->xmlWriter->writeAttribute('end', $end);
}

$this->xmlWriter->writeAttribute('crap', $crap);

$this->xmlWriter->writeAttribute('executable', $executable);
$this->xmlWriter->writeAttribute('executed', $executed);
$this->xmlWriter->writeAttribute('coverage', $coverage);
}
}
