<?php

declare(strict_types=1);

namespace Brick\Math\Exception;

use function dechex;
use function ord;
use function sprintf;
use function strtoupper;




final class NumberFormatException extends MathException
{
/**
@pure
*/
public static function invalidFormat(string $value): self
{
return new self(sprintf(
'The given value "%s" does not represent a valid number.',
$value,
));
}

/**
@pure


*/
public static function charNotInAlphabet(string $char): self
{
return new self(sprintf(
'Character %s is not valid in the given alphabet.',
self::charToString($char),
));
}

/**
@pure
*/
private static function charToString(string $char): string
{
$ord = ord($char);

if ($ord < 32 || $ord > 126) {
$char = strtoupper(dechex($ord));

if ($ord < 16) {
$char = '0' . $char;
}

return '0x' . $char;
}

return '"' . $char . '"';
}
}
