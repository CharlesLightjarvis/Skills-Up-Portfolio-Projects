<?php declare(strict_types=1);








namespace PHPUnit\TestRunner\TestResult;

use PHPUnit\Event\Test\AfterLastTestMethodFailed;
use PHPUnit\Event\Test\AfterLastTestMethodFailedSubscriber;

/**
@no-named-arguments


*/
final readonly class AfterTestClassMethodFailedSubscriber extends Subscriber implements AfterLastTestMethodFailedSubscriber
{
public function notify(AfterLastTestMethodFailed $event): void
{
$this->collector()->afterTestClassMethodFailed($event);
}
}
