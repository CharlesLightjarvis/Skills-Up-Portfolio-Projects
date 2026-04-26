<?php declare(strict_types=1);








namespace PHPUnit\Event\TestRunner;

use PHPUnit\Event\Subscriber;

/**
@no-named-arguments
*/
interface StaticAnalysisForCodeCoverageFinishedSubscriber extends Subscriber
{
public function notify(StaticAnalysisForCodeCoverageFinished $event): void;
}
