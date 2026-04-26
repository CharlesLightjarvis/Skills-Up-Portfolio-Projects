<?php declare(strict_types=1);








namespace PHPUnit\Event\TestRunner;

use PHPUnit\Event\Subscriber;

/**
@no-named-arguments
*/
interface StaticAnalysisForCodeCoverageStartedSubscriber extends Subscriber
{
public function notify(StaticAnalysisForCodeCoverageStarted $event): void;
}
