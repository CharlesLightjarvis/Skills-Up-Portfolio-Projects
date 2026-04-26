<?php declare(strict_types=1);








namespace PHPUnit\TextUI\Output\Default\ProgressPrinter;

use PHPUnit\Event\Test\PhpunitNoticeTriggered;
use PHPUnit\Event\Test\PhpunitNoticeTriggeredSubscriber;

/**
@no-named-arguments


*/
final readonly class TestTriggeredPhpunitNoticeSubscriber extends Subscriber implements PhpunitNoticeTriggeredSubscriber
{
public function notify(PhpunitNoticeTriggered $event): void
{
$this->printer()->testTriggeredPhpunitNotice();
}
}
