<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Report\Xml;

use XMLWriter;




abstract class Node
{
protected readonly XMLWriter $xmlWriter;

public function __construct(XMLWriter $xmlWriter)
{
$this->xmlWriter = $xmlWriter;
}

public function totals(): Totals
{
return new Totals($this->xmlWriter);
}

public function addDirectory(): Directory
{
return new Directory($this->xmlWriter);
}

public function addFile(): File
{
return new File($this->xmlWriter);
}

public function getWriter(): XMLWriter
{
return $this->xmlWriter;
}
}
