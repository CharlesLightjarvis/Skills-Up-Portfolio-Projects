<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Report\Xml;

use function basename;
use function dirname;
use DOMDocument;
use XMLWriter;




final class Report extends File
{
private readonly string $name;
private readonly string $sha1;

public function __construct(XMLWriter $xmlWriter, string $name, string $sha1)
{









parent::__construct($xmlWriter);

$this->name = $name;
$this->sha1 = $sha1;

$xmlWriter->startDocument();
$xmlWriter->startElement('phpunit');
$xmlWriter->writeAttribute('xmlns', Facade::XML_NAMESPACE);
$xmlWriter->startElement('file');
$xmlWriter->writeAttribute('name', basename($this->name));
$xmlWriter->writeAttribute('path', dirname($this->name));
$xmlWriter->writeAttribute('hash', $this->sha1);
}

public function finalize(): void
{
$this->xmlWriter->endElement();
$this->xmlWriter->endElement();

$this->xmlWriter->endDocument();
$this->xmlWriter->flush();
}

public function functionObject(
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

public function classObject(
string $name,
string $namespace,
int $start,
int $executable,
int $executed,
float $crap
): Unit {
return new Unit($this->xmlWriter, $name, $namespace, $start, $executable, $executed, $crap);
}

public function traitObject(
string $name,
string $namespace,
int $start,
int $executable,
int $executed,
float $crap
): Unit {
return new Unit($this->xmlWriter, $name, $namespace, $start, $executable, $executed, $crap);
}

public function source(): Source
{
return new Source($this->xmlWriter);
}
}
