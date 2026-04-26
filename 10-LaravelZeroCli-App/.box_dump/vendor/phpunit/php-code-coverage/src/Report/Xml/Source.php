<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Report\Xml;

use TheSeer\Tokenizer\NamespaceUri;
use TheSeer\Tokenizer\Tokenizer;
use TheSeer\Tokenizer\XMLSerializer;
use XMLWriter;




final readonly class Source
{
private XMLWriter $xmlWriter;

public function __construct(XMLWriter $xmlWriter)
{
$this->xmlWriter = $xmlWriter;
}

public function setSourceCode(string $source): void
{
$tokens = (new Tokenizer)->parse($source);
(new XMLSerializer(new NamespaceUri(Facade::XML_NAMESPACE)))->appendToWriter($this->xmlWriter, $tokens);
}
}
