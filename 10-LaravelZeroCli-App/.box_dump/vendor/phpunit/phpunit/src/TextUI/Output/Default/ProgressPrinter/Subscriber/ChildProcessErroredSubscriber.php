<?php declare(strict_types=1);








namespace PHPUnit\TextUI\Output\Default\ProgressPrinter;

use PHPUnit\Event\TestRunner\ChildProcessErrored;

/**
@no-named-arguments


*/
final readonly class ChildProcessErroredSubscriber extends Subscriber implements \PHPUnit\Event\TestRunner\ChildProcessErroredSubscriber
{
public function notify(ChildProcessErrored $event): void
{
$this->printer()->childProcessErrored($event);
}
}
