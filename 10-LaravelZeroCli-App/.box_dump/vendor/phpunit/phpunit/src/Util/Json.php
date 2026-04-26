<?php declare(strict_types=1);








namespace PHPUnit\Util;

use const JSON_ERROR_NONE;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;
use const SORT_STRING;
use function assert;
use function is_object;
use function is_scalar;
use function json_decode;
use function json_encode;
use function json_last_error;
use function ksort;

/**
@no-named-arguments


*/
final readonly class Json
{



public static function prettify(string $json): string
{
$decodedJson = json_decode($json, false);

if (json_last_error() !== JSON_ERROR_NONE) {
throw new InvalidJsonException;
}

$result = json_encode($decodedJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

assert($result !== false);

return $result;
}








public static function canonicalize(string $json): array
{
$decodedJson = json_decode($json);

if (json_last_error() !== JSON_ERROR_NONE) {
return [true, null];
}

self::recursiveSort($decodedJson);

$reencodedJson = json_encode($decodedJson);

return [false, $reencodedJson];
}







private static function recursiveSort(mixed &$json): void
{
if ($json === null || $json === [] || is_scalar($json)) {
return;
}

$isObject = is_object($json);

if ($isObject) {







$json = (array) $json;
ksort($json, SORT_STRING);
}

foreach ($json as &$value) {
self::recursiveSort($value);
}

if ($isObject) {
$json = (object) $json;
}
}
}
