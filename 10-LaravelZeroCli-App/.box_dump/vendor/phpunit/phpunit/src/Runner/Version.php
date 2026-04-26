<?php declare(strict_types=1);








namespace PHPUnit\Runner;

use function array_slice;
use function assert;
use function dirname;
use function explode;
use function implode;
use function str_contains;
use SebastianBergmann\Version as VersionId;

/**
@no-named-arguments
*/
final class Version
{
private static string $pharVersion = '';
private static string $version = '';




public static function id(): string
{
if (self::$pharVersion !== '') {
return self::$pharVersion;
}

if (self::$version === '') {
self::$version = (new VersionId('12.5.23', dirname(__DIR__, 2)))->asString();
}

return self::$version;
}




public static function series(): string
{
if (str_contains(self::id(), '-')) {
$version = explode('-', self::id(), 2)[0];
} else {
$version = self::id();
}

return implode('.', array_slice(explode('.', $version), 0, 2));
}




public static function majorVersionNumber(): int
{
$majorVersion = (int) explode('.', self::series())[0];
assert($majorVersion > 0);

return $majorVersion;
}




public static function getVersionString(): string
{
return 'PHPUnit ' . self::id() . ' by Sebastian Bergmann and contributors.';
}
}
