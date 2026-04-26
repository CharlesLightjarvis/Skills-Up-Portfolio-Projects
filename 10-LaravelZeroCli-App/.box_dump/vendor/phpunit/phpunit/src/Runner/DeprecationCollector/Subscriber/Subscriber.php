<?php declare(strict_types=1);








namespace PHPUnit\Runner\DeprecationCollector;

/**
@no-named-arguments


*/
abstract class Subscriber
{
private readonly Collector|InIsolationCollector $collector;

public function __construct(Collector|InIsolationCollector $collector)
{
$this->collector = $collector;
}

protected function collector(): Collector|InIsolationCollector
{
return $this->collector;
}
}
