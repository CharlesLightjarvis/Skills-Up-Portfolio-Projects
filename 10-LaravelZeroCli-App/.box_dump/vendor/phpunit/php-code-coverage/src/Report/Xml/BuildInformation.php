<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Report\Xml;

use DateTimeImmutable;
use SebastianBergmann\Environment\Runtime;
use XMLWriter;




final readonly class BuildInformation
{
public function __construct(
XMLWriter $xmlWriter,
Runtime $runtime,
DateTimeImmutable $buildDate,
string $phpUnitVersion,
string $coverageVersion,
string $driverExtensionName,
string $driverExtensionVersion,
) {
$xmlWriter->startElement('build');
$xmlWriter->writeAttribute('time', $buildDate->format('D M j G:i:s T Y'));
$xmlWriter->writeAttribute('phpunit', $phpUnitVersion);
$xmlWriter->writeAttribute('coverage', $coverageVersion);

$xmlWriter->startElement('runtime');
$xmlWriter->writeAttribute('name', $runtime->getName());
$xmlWriter->writeAttribute('version', $runtime->getVersion());
$xmlWriter->writeAttribute('url', $runtime->getVendorUrl());
$xmlWriter->endElement();

$xmlWriter->startElement('driver');
$xmlWriter->writeAttribute('name', $driverExtensionName);
$xmlWriter->writeAttribute('version', $driverExtensionVersion);
$xmlWriter->endElement();

$xmlWriter->endElement();
}
}
