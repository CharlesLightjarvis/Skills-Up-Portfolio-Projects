<?php declare(strict_types=1);








namespace PHPUnit\Event\Test;

use const PHP_EOL;
use function sprintf;
use PHPUnit\Event\Code;
use PHPUnit\Event\Code\Throwable;
use PHPUnit\Event\Event;
use PHPUnit\Event\Telemetry;

/**
@immutable
@no-named-arguments

*/
final readonly class PostConditionErrored implements Event
{
private Telemetry\Info $telemetryInfo;
private Code\TestMethod $test;
private Code\ClassMethod $calledMethod;
private Throwable $throwable;

public function __construct(Telemetry\Info $telemetryInfo, Code\TestMethod $test, Code\ClassMethod $calledMethod, Throwable $throwable)
{
$this->telemetryInfo = $telemetryInfo;
$this->test = $test;
$this->calledMethod = $calledMethod;
$this->throwable = $throwable;
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

public function throwable(): Throwable
{
return $this->throwable;
}




public function asString(): string
{
$message = $this->throwable->message();

if ($message !== '') {
$message = PHP_EOL . $message;
}

return sprintf(
'Post Condition Method Errored (%s::%s)%s',
$this->calledMethod->className(),
$this->calledMethod->methodName(),
$message,
);
}
}
