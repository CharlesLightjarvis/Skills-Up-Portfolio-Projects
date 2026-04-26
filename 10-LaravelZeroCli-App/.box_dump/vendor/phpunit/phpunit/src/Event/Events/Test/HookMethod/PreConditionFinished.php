<?php declare(strict_types=1);








namespace PHPUnit\Event\Test;

use const PHP_EOL;
use function sprintf;
use PHPUnit\Event\Code;
use PHPUnit\Event\Event;
use PHPUnit\Event\Telemetry;

/**
@immutable
@no-named-arguments

*/
final readonly class PreConditionFinished implements Event
{
private Telemetry\Info $telemetryInfo;
private Code\TestMethod $test;




private array $calledMethods;

public function __construct(Telemetry\Info $telemetryInfo, Code\TestMethod $test, Code\ClassMethod ...$calledMethods)
{
$this->telemetryInfo = $telemetryInfo;
$this->test = $test;
$this->calledMethods = $calledMethods;
}

public function telemetryInfo(): Telemetry\Info
{
return $this->telemetryInfo;
}

public function test(): Code\TestMethod
{
return $this->test;
}






public function testClassName(): string
{
return $this->test->className();
}




public function calledMethods(): array
{
return $this->calledMethods;
}




public function asString(): string
{
$buffer = 'Pre Condition Method Finished:';

foreach ($this->calledMethods as $calledMethod) {
$buffer .= sprintf(
PHP_EOL . '- %s::%s',
$calledMethod->className(),
$calledMethod->methodName(),
);
}

return $buffer;
}
}
