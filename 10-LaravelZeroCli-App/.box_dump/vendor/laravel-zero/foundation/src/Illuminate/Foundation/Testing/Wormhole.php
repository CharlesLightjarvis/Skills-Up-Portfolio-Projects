<?php

namespace Illuminate\Foundation\Testing;

use Illuminate\Support\Carbon;

class Wormhole
{





public $value;






public function __construct($value)
{
$this->value = $value;
}

/**
@template





*/
public function microsecond($callback = null)
{
return $this->microseconds($callback);
}

/**
@template





*/
public function microseconds($callback = null)
{
Carbon::setTestNow(Carbon::now()->addMicroseconds($this->value));

return $this->handleCallback($callback);
}

/**
@template





*/
public function millisecond($callback = null)
{
return $this->milliseconds($callback);
}

/**
@template





*/
public function milliseconds($callback = null)
{
Carbon::setTestNow(Carbon::now()->addMilliseconds($this->value));

return $this->handleCallback($callback);
}

/**
@template





*/
public function second($callback = null)
{
return $this->seconds($callback);
}

/**
@template





*/
public function seconds($callback = null)
{
Carbon::setTestNow(Carbon::now()->addSeconds($this->value));

return $this->handleCallback($callback);
}

/**
@template





*/
public function minute($callback = null)
{
return $this->minutes($callback);
}

/**
@template





*/
public function minutes($callback = null)
{
Carbon::setTestNow(Carbon::now()->addMinutes($this->value));

return $this->handleCallback($callback);
}

/**
@template





*/
public function hour($callback = null)
{
return $this->hours($callback);
}

/**
@template





*/
public function hours($callback = null)
{
Carbon::setTestNow(Carbon::now()->addHours($this->value));

return $this->handleCallback($callback);
}

/**
@template





*/
public function day($callback = null)
{
return $this->days($callback);
}

/**
@template





*/
public function days($callback = null)
{
Carbon::setTestNow(Carbon::now()->addDays($this->value));

return $this->handleCallback($callback);
}

/**
@template





*/
public function week($callback = null)
{
return $this->weeks($callback);
}

/**
@template





*/
public function weeks($callback = null)
{
Carbon::setTestNow(Carbon::now()->addWeeks($this->value));

return $this->handleCallback($callback);
}

/**
@template





*/
public function month($callback = null)
{
return $this->months($callback);
}

/**
@template





*/
public function months($callback = null)
{
Carbon::setTestNow(Carbon::now()->addMonths($this->value));

return $this->handleCallback($callback);
}

/**
@template





*/
public function year($callback = null)
{
return $this->years($callback);
}

/**
@template





*/
public function years($callback = null)
{
Carbon::setTestNow(Carbon::now()->addYears($this->value));

return $this->handleCallback($callback);
}






public static function back()
{
Carbon::setTestNow();

return Carbon::now();
}

/**
@template





*/
protected function handleCallback($callback)
{
if ($callback) {
return tap($callback(), function () {
Carbon::setTestNow();
});
}
}
}
