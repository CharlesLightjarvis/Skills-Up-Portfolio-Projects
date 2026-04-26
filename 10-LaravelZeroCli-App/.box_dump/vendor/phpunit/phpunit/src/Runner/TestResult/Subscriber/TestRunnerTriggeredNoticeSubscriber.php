<?php declare(strict_types=1);








namespace PHPUnit\TestRunner\TestResult;

use PHPUnit\Event\TestRunner\NoticeTriggered;
use PHPUnit\Event\TestRunner\NoticeTriggeredSubscriber;

/**
@no-named-arguments


*/
final readonly class TestRunnerTriggeredNoticeSubscriber extends Subscriber implements NoticeTriggeredSubscriber
{
public function notify(NoticeTriggered $event): void
{
$this->collector()->testRunnerTriggeredNotice($event);
}
}
