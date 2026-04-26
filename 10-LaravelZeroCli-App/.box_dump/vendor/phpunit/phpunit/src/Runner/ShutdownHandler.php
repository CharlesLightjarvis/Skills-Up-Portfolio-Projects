<?php declare(strict_types=1);








namespace PHPUnit\Runner;

use const PHP_EOL;
use function getmypid;
use function register_shutdown_function;
use function rtrim;

/**
@no-named-arguments


*/
final class ShutdownHandler
{
private static bool $registered = false;
private static string $message = '';

public static function setMessage(string $message): void
{
self::register();

self::$message = $message;
}

public static function resetMessage(): void
{
self::$message = '';
}

private static function register(): void
{
if (self::$registered) {
return;
}

self::$registered = true;
$pid = getmypid();

register_shutdown_function(
static function () use ($pid): void
{
$message = rtrim(self::$message);

if ($message === '' || $pid !== getmypid()) {
return;
}

print $message . PHP_EOL;

exit(2);
},
);
}
}
