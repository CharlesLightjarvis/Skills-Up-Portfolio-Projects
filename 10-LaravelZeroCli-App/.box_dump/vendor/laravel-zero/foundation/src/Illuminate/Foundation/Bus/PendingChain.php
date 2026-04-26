<?php

namespace Illuminate\Foundation\Bus;

use Closure;
use Illuminate\Bus\ChainedBatch;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Conditionable;
use Laravel\SerializableClosure\SerializableClosure;

use function Illuminate\Support\enum_value;

class PendingChain
{
use Conditionable;






public $job;






public $chain;






public $connection;






public $queue;






public $delay;






public $catchCallbacks = [];







public function __construct($job, $chain)
{
$this->job = $job;
$this->chain = $chain;
}







public function onConnection($connection)
{
$this->connection = enum_value($connection);

return $this;
}







public function onQueue($queue)
{
$this->queue = enum_value($queue);

return $this;
}







public function prepend($job)
{
$jobs = ChainedBatch::prepareNestedBatches(
Collection::wrap($job)
);

if ($this->job) {
array_unshift($this->chain, $this->job);
}

$this->job = $jobs->shift();

array_unshift($this->chain, ...$jobs->toArray());

return $this;
}







public function append($job)
{
$jobs = ChainedBatch::prepareNestedBatches(
Collection::wrap($job)
);

if (! $this->job) {
$this->job = $jobs->shift();
}

array_push($this->chain, ...$jobs->toArray());

return $this;
}







public function delay($delay)
{
$this->delay = $delay;

return $this;
}







public function catch($callback)
{
$this->catchCallbacks[] = $callback instanceof Closure
? new SerializableClosure($callback)
: $callback;

return $this;
}






public function catchCallbacks()
{
return $this->catchCallbacks ?? [];
}






public function dispatch()
{
if (is_string($this->job)) {
$firstJob = new $this->job(...func_get_args());
} elseif ($this->job instanceof Closure) {
$firstJob = CallQueuedClosure::create($this->job);
} else {
$firstJob = $this->job;
}

if ($this->connection) {
$firstJob->chainConnection = $this->connection;
$firstJob->connection = $firstJob->connection ?: $this->connection;
}

if ($this->queue) {
$firstJob->chainQueue = $this->queue;
$firstJob->queue = $firstJob->queue ?: $this->queue;
}

if ($this->delay) {
$firstJob->delay = ! is_null($firstJob->delay) ? $firstJob->delay : $this->delay;
}

$firstJob->chain($this->chain);
$firstJob->chainCatchCallbacks = $this->catchCallbacks();

return app(Dispatcher::class)->dispatch($firstJob);
}







public function dispatchIf($boolean)
{
return value($boolean) ? $this->dispatch() : null;
}







public function dispatchUnless($boolean)
{
return ! value($boolean) ? $this->dispatch() : null;
}
}
