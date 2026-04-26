<?php declare(strict_types=1);








namespace PHPUnit\Event;

use PHPUnit\Runner\DeprecationCollector\Facade as DeprecationCollector;
use PHPUnit\Runner\DeprecationCollector\TestTriggeredDeprecationSubscriber;

/**
@no-named-arguments


*/
final class CollectingDispatcher implements Dispatcher
{
private EventCollection $events;
private DirectDispatcher $isolatedDirectDispatcher;

public function __construct(DirectDispatcher $directDispatcher)
{
$this->isolatedDirectDispatcher = $directDispatcher;
$this->events = new EventCollection;

$this->isolatedDirectDispatcher->registerSubscriber(new TestTriggeredDeprecationSubscriber(DeprecationCollector::collector()));
}

public function dispatch(Event $event): void
{
$this->events->add($event);

try {
$this->isolatedDirectDispatcher->dispatch($event);
} catch (UnknownEventTypeException) {

}
}

public function flush(): EventCollection
{
$events = $this->events;

$this->events = new EventCollection;

return $events;
}
}
