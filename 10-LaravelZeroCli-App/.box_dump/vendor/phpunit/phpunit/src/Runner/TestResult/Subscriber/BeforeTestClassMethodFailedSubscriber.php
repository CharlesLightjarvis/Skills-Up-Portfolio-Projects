<?php declare(strict_types=1);








namespace PHPUnit\TestRunner\TestResult;

use PHPUnit\Event\Test\BeforeFirstTestMethodFailed;
use PHPUnit\Event\Test\BeforeFirstTestMethodFailedSubscriber;

/**
@no-named-arguments


*/
final readonly class BeforeTestClassMethodFailedSubscriber extends Subscriber implements BeforeFirstTestMethodFailedSubscriber
{
public function notify(BeforeFirstTestMethodFailed $event): void
{
$this->collector()->beforeTestClassMethodFailed($event);
}
}
