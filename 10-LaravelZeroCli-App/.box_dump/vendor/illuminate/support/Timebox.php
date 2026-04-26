<?php

namespace Illuminate\Support;

use Throwable;

class Timebox
{





public $earlyReturn = false;

/**
@template








*/
public function call(callable $callback, int $microseconds)
{
$exception = null;

$start = microtime(true);

try {
$result = $callback($this);
} catch (Throwable $caught) {
$exception = $caught;
}

$remainder = (int) ($microseconds - ((microtime(true) - $start) * 1_000_000));

if (! $this->earlyReturn && $remainder > 0) {
$this->usleep($remainder);
}

if ($exception) {
throw $exception;
}

return $result;
}






public function returnEarly()
{
$this->earlyReturn = true;

return $this;
}






public function dontReturnEarly()
{
$this->earlyReturn = false;

return $this;
}







protected function usleep(int $microseconds)
{
Sleep::usleep($microseconds);
}
}
