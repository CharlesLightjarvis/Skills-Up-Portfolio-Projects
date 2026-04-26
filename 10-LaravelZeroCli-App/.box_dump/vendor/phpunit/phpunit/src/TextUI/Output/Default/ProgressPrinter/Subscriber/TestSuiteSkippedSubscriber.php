<?php declare(strict_types=1);








namespace PHPUnit\TextUI\Output\Default\ProgressPrinter;

use PHPUnit\Event\TestSuite\Skipped;
use PHPUnit\Event\TestSuite\SkippedSubscriber;

/**
@no-named-arguments


*/
final readonly class TestSuiteSkippedSubscriber extends Subscriber implements SkippedSubscriber
{
public function notify(Skipped $event): void
{
$this->printer()->testSuiteSkipped($event->testSuite()->count());
}
}
