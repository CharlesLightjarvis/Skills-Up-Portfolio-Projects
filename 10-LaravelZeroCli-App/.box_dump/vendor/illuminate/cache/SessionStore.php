<?php

namespace Illuminate\Cache;

use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Carbon;
use Illuminate\Support\InteractsWithTime;

class SessionStore implements Store
{
use InteractsWithTime, RetrievesMultipleKeys;






public $key;






public $session;







public function __construct($session, $key = '_cache')
{
$this->key = $key;
$this->session = $session;
}






public function all()
{
return $this->session->get($this->key, []);
}







public function get($key)
{
if (! $this->session->exists($this->itemKey($key))) {
return;
}

$item = $this->session->get($this->itemKey($key));

$expiresAt = $item['expiresAt'] ?? 0;

if ($this->isExpired($expiresAt)) {
$this->forget($key);

return;
}

return $item['value'];
}







protected function isExpired($expiresAt)
{
return $expiresAt !== 0 && (Carbon::now()->getPreciseTimestamp(3) / 1000) >= $expiresAt;
}









public function put($key, $value, $seconds)
{
$this->session->put($this->itemKey($key), [
'value' => $value,
'expiresAt' => $this->toTimestamp($seconds),
]);

return true;
}







protected function toTimestamp($seconds)
{
return $seconds > 0 ? (Carbon::now()->getPreciseTimestamp(3) / 1000) + $seconds : 0;
}








public function increment($key, $value = 1)
{
if (! is_null($existing = $this->get($key))) {
return tap(((int) $existing) + $value, function ($incremented) use ($key) {
$this->session->put($this->itemKey("{$key}.value"), $incremented);
});
}

$this->forever($key, $value);

return $value;
}








public function decrement($key, $value = 1)
{
return $this->increment($key, $value * -1);
}








public function forever($key, $value)
{
return $this->put($key, $value, 0);
}







public function forget($key)
{
if ($this->session->exists($this->itemKey($key))) {
$this->session->forget($this->itemKey($key));

return true;
}

return false;
}






public function flush()
{
$this->session->put($this->key, []);

return true;
}






public function itemKey($key)
{
return "{$this->key}.{$key}";
}






public function getPrefix()
{
return '';
}
}
