<?php declare(strict_types=1);








namespace PHPUnit\Event\Test;

use function sprintf;
use PHPUnit\Event\Code;
use PHPUnit\Event\Event;
use PHPUnit\Event\Telemetry;

/**
@immutable
@no-named-arguments

*/
final readonly class PreConditionCalled implements Event
{
private Telemetry\Info$telemetryInfo;
private Code\TestMethod $test;
private Code\ClassMethod $calledMethod;

public function __construct(Telemetry\Info $telemetryInfo, Code\TestMethod $test, Code\ClassMethod $calledMethod)
{
$this->telemetryInfo = $telemetryInfo;
$this->test = $test;
$this->calledMethod = $calledMethod;
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

public function calledMethod(): Code\ClassMethod
{
return $this->calledMethod;
}




public function asString(): string
{
return sprintf(
'Pre Condition Method Called (%s::%s)',
$this->calledMethod->className(),
$this->calledMethod->methodName(),
);
}
}
