<?php declare(strict_types=1);








namespace PHPUnit\Event\Test;

use const PHP_EOL;
use function implode;
use function sprintf;
use PHPUnit\Event\Code\Test;
use PHPUnit\Event\Event;
use PHPUnit\Event\Telemetry;

/**
@immutable
@no-named-arguments

*/
final readonly class PhpunitWarningTriggered implements Event
{
private Telemetry\Info $telemetryInfo;
private Test $test;




private string $message;
private bool $ignoredByTest;




public function __construct(Telemetry\Info $telemetryInfo, Test $test, string $message, bool $ignoredByTest)
{
$this->telemetryInfo = $telemetryInfo;
$this->test = $test;
$this->message = $message;
$this->ignoredByTest = $ignoredByTest;
}

public function telemetryInfo(): Telemetry\Info
{
return $this->telemetryInfo;
}

public function test(): Test
{
return $this->test;
}




public function message(): string
{
return $this->message;
}

public function ignoredByTest(): bool
{
return $this->ignoredByTest;
}




public function asString(): string
{
$message = $this->message;

if ($message !== '') {
$message = PHP_EOL . $message;
}

$details = [$this->test->id()];

if ($this->ignoredByTest) {
$details[] = 'ignored by test';
}

return sprintf(
'Test Triggered PHPUnit Warning (%s)%s',
implode(', ', $details),
$message,
);
}
}
