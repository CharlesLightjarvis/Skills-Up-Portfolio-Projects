<?php declare(strict_types=1);








namespace PHPUnit\Event\Test;

use const PHP_EOL;
use function sprintf;
use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Event;
use PHPUnit\Event\Telemetry;

/**
@immutable
@no-named-arguments

*/
final readonly class AdditionalInformationProvided implements Event
{
private Telemetry\Info $telemetryInfo;
private TestMethod $test;




private string $additionalInformation;




public function __construct(Telemetry\Info $telemetryInfo, TestMethod $test, string $additionalInformation)
{
$this->telemetryInfo = $telemetryInfo;
$this->test = $test;
$this->additionalInformation = $additionalInformation;
}

public function telemetryInfo(): Telemetry\Info
{
return $this->telemetryInfo;
}

public function test(): TestMethod
{
return $this->test;
}




public function additionalInformation(): string
{
return $this->additionalInformation;
}




public function asString(): string
{
return sprintf(
'Test Provided Additional Information%s%s',
PHP_EOL,
$this->additionalInformation,
);
}
}
