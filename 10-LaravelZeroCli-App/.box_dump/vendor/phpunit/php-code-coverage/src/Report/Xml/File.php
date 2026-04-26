<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Report\Xml;

use XMLWriter;




class File
{
protected XMLWriter $xmlWriter;

public function __construct(XMLWriter $xmlWriter)
{
$this->xmlWriter = $xmlWriter;
}

public function getWriter(): XMLWriter
{
return $this->xmlWriter;
}

public function totals(): Totals
{
return new Totals($this->xmlWriter);
}

public function lineCoverage(string $line): Coverage
{
return new Coverage($this->xmlWriter, $line);
}
}
