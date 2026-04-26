<?php

declare(strict_types=1);










namespace Carbon\Traits;

use Carbon\Carbon;
use Carbon\CarbonImmutable;






trait Mutability
{
use Cast;




public static function isMutable(): bool
{
return false;
}




public static function isImmutable(): bool
{
return !static::isMutable();
}




public function toMutable(): Carbon
{
return $this->cast(Carbon::class);
}




public function toImmutable(): CarbonImmutable
{

if ($this::class === CarbonImmutable::class) {
return $this;
}

return $this->cast(CarbonImmutable::class);
}
}
