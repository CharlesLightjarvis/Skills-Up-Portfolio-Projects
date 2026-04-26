<?php

namespace Illuminate\Support;

use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Illuminate\Support\Defer\DeferredCallback;
use Illuminate\Support\Defer\DeferredCallbackCollection;
use Illuminate\Support\Facades\Date;
use Symfony\Component\Process\PhpExecutableFinder;

if (! function_exists('Illuminate\Support\defer')) {








function defer(?callable $callback = null, ?string $name = null, bool $always = false): DeferredCallback|DeferredCallbackCollection
{
if ($callback === null) {
return app(DeferredCallbackCollection::class);
}

return tap(
new DeferredCallback($callback, $name, $always),
fn ($deferred) => app(DeferredCallbackCollection::class)[] = $deferred
);
}
}

if (! function_exists('Illuminate\Support\php_binary')) {



function php_binary(): string
{
return (new PhpExecutableFinder)->find(false) ?: 'php';
}
}

if (! function_exists('Illuminate\Support\artisan_binary')) {



function artisan_binary(): string
{
return defined('ARTISAN_BINARY') ? ARTISAN_BINARY : 'artisan';
}
}



if (! function_exists('Illuminate\Support\now')) {






function now($tz = null): CarbonInterface
{
return Date::now(enum_value($tz));
}
}

if (! function_exists('Illuminate\Support\microseconds')) {



function microseconds(int|float $microseconds): CarbonInterval
{
return CarbonInterval::microseconds($microseconds);
}
}

if (! function_exists('Illuminate\Support\milliseconds')) {



function milliseconds(int|float $milliseconds): CarbonInterval
{
return CarbonInterval::milliseconds($milliseconds);
}
}

if (! function_exists('Illuminate\Support\seconds')) {



function seconds(int|float $seconds): CarbonInterval
{
return CarbonInterval::seconds($seconds);
}
}

if (! function_exists('Illuminate\Support\minutes')) {



function minutes(int|float $minutes): CarbonInterval
{
return CarbonInterval::minutes($minutes);
}
}

if (! function_exists('Illuminate\Support\hours')) {



function hours(int|float $hours): CarbonInterval
{
return CarbonInterval::hours($hours);
}
}

if (! function_exists('Illuminate\Support\days')) {



function days(int|float $days): CarbonInterval
{
return CarbonInterval::days($days);
}
}

if (! function_exists('Illuminate\Support\weeks')) {



function weeks(int $weeks): CarbonInterval
{
return CarbonInterval::weeks($weeks);
}
}

if (! function_exists('Illuminate\Support\months')) {



function months(int $months): CarbonInterval
{
return CarbonInterval::months($months);
}
}

if (! function_exists('Illuminate\Support\years')) {



function years(int $years): CarbonInterval
{
return CarbonInterval::years($years);
}
}
