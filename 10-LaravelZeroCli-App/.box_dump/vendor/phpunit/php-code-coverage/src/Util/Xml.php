<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Util;

use const PHP_EOL;
use function libxml_clear_errors;
use function libxml_get_errors;
use function libxml_use_internal_errors;
use DOMDocument;
use SebastianBergmann\CodeCoverage\XmlException;




final readonly class Xml
{





public static function asString(DOMDocument $document): string
{
$xmlErrorHandling = libxml_use_internal_errors(true);

$document->formatOutput = true;
$document->preserveWhiteSpace = false;

$buffer = $document->saveXML();

if ($buffer === false) {
$message = 'Unable to generate the XML';

foreach (libxml_get_errors() as $error) {
$message .= PHP_EOL . $error->message;
}

throw new XmlException($message);
}

libxml_clear_errors();
libxml_use_internal_errors($xmlErrorHandling);

return $buffer;
}
}
