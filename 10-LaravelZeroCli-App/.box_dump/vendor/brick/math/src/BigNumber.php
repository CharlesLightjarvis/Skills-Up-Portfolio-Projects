<?php

declare(strict_types=1);

namespace Brick\Math;

use Brick\Math\Exception\DivisionByZeroException;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use InvalidArgumentException;
use JsonSerializable;
use Override;
use Stringable;

use function array_shift;
use function assert;
use function filter_var;
use function is_float;
use function is_int;
use function is_nan;
use function is_null;
use function ltrim;
use function preg_match;
use function str_contains;
use function str_repeat;
use function strlen;
use function substr;
use function trigger_error;

use const E_USER_DEPRECATED;
use const FILTER_VALIDATE_INT;
use const PREG_UNMATCHED_AS_NULL;

/**
@phpstan-sealed





*/
abstract readonly class BigNumber implements JsonSerializable, Stringable
{



private const PARSE_REGEXP_NUMERICAL =
'/^' .
'(?<sign>[\-\+])?' .
'(?<integral>[0-9]+)?' .
'(?<point>\.)?' .
'(?<fractional>[0-9]+)?' .
'(?:[eE](?<exponent>[\-\+]?[0-9]+))?' .
'$/';




private const PARSE_REGEXP_RATIONAL =
'/^' .
'(?<sign>[\-\+])?' .
'(?<numerator>[0-9]+)' .
'\/' .
'(?<denominator>[0-9]+)' .
'$/';

/**
@pure



















*/
final public static function of(BigNumber|int|float|string $value): static
{
$value = self::_of($value);

if (static::class === BigNumber::class) {
assert($value instanceof static);

return $value;
}

return static::from($value);
}

/**
@pure










*/
final public static function ofNullable(BigNumber|int|float|string|null $value): ?static
{
if (is_null($value)) {
return null;
}

return static::of($value);
}

/**
@pure












*/
final public static function min(BigNumber|int|float|string ...$values): static
{
$min = null;

foreach ($values as $value) {
$value = static::of($value);

if ($min === null || $value->isLessThan($min)) {
$min = $value;
}
}

if ($min === null) {
throw new InvalidArgumentException(__METHOD__ . '() expects at least one value.');
}

return $min;
}

/**
@pure












*/
final public static function max(BigNumber|int|float|string ...$values): static
{
$max = null;

foreach ($values as $value) {
$value = static::of($value);

if ($max === null || $value->isGreaterThan($max)) {
$max = $value;
}
}

if ($max === null) {
throw new InvalidArgumentException(__METHOD__ . '() expects at least one value.');
}

return $max;
}

/**
@pure















*/
final public static function sum(BigNumber|int|float|string ...$values): static
{
$first = array_shift($values);

if ($first === null) {
throw new InvalidArgumentException(__METHOD__ . '() expects at least one value.');
}

$sum = static::of($first);

foreach ($values as $value) {
$sum = self::add($sum, static::of($value));
}

assert($sum instanceof static);

return $sum;
}

/**
@pure




*/
final public function isEqualTo(BigNumber|int|float|string $that): bool
{
return $this->compareTo($that) === 0;
}

/**
@pure




*/
final public function isLessThan(BigNumber|int|float|string $that): bool
{
return $this->compareTo($that) < 0;
}

/**
@pure




*/
final public function isLessThanOrEqualTo(BigNumber|int|float|string $that): bool
{
return $this->compareTo($that) <= 0;
}

/**
@pure




*/
final public function isGreaterThan(BigNumber|int|float|string $that): bool
{
return $this->compareTo($that) > 0;
}

/**
@pure




*/
final public function isGreaterThanOrEqualTo(BigNumber|int|float|string $that): bool
{
return $this->compareTo($that) >= 0;
}

/**
@pure


*/
final public function isZero(): bool
{
return $this->getSign() === 0;
}

/**
@pure


*/
final public function isNegative(): bool
{
return $this->getSign() < 0;
}

/**
@pure


*/
final public function isNegativeOrZero(): bool
{
return $this->getSign() <= 0;
}

/**
@pure


*/
final public function isPositive(): bool
{
return $this->getSign() > 0;
}

/**
@pure


*/
final public function isPositiveOrZero(): bool
{
return $this->getSign() >= 0;
}

/**
@pure


*/
final public function abs(): static
{
return $this->isNegative() ? $this->negated() : $this;
}

/**
@pure


*/
abstract public function negated(): static;

/**
@pure






*/
abstract public function getSign(): int;

/**
@pure








*/
abstract public function compareTo(BigNumber|int|float|string $that): int;

/**
@pure












*/
final public function clamp(BigNumber|int|float|string $min, BigNumber|int|float|string $max): static
{
$min = static::of($min);
$max = static::of($max);

if ($min->isGreaterThan($max)) {
throw new InvalidArgumentException('Minimum value must be less than or equal to maximum value.');
}

if ($this->isLessThan($min)) {
return $min;
}

if ($this->isGreaterThan($max)) {
return $max;
}

return $this;
}

/**
@pure




*/
abstract public function toBigInteger(): BigInteger;

/**
@pure




*/
abstract public function toBigDecimal(): BigDecimal;

/**
@pure


*/
abstract public function toBigRational(): BigRational;

/**
@pure









*/
abstract public function toScale(int $scale, RoundingMode $roundingMode = RoundingMode::Unnecessary): BigDecimal;

/**
@pure







*/
abstract public function toInt(): int;

/**
@pure









*/
abstract public function toFloat(): float;

/**
@pure





*/
abstract public function toString(): string;

#[Override]
final public function jsonSerialize(): string
{
return $this->toString();
}

/**
@pure
*/
final public function __toString(): string
{
return $this->toString();
}

/**
@pure




*/
abstract protected static function from(BigNumber $number): static;

/**
@pure




*/
final protected function newBigInteger(string $value): BigInteger
{
return new BigInteger($value);
}

/**
@pure




*/
final protected function newBigDecimal(string $value, int $scale = 0): BigDecimal
{
return new BigDecimal($value, $scale);
}

/**
@pure




*/
final protected function newBigRational(BigInteger $numerator, BigInteger $denominator, bool $checkDenominator): BigRational
{
return new BigRational($numerator, $denominator, $checkDenominator);
}

/**
@pure



*/
private static function _of(BigNumber|int|float|string $value): BigNumber
{
if ($value instanceof BigNumber) {
return $value;
}

if (is_int($value)) {
return new BigInteger((string) $value);
}

if (is_float($value)) {

trigger_error(
'Passing floats to BigNumber::of() and arithmetic methods is deprecated and will be removed in 0.15. ' .
'Cast the float to string explicitly to preserve the previous behaviour.',
E_USER_DEPRECATED,
);

if (is_nan($value)) {
$value = 'NAN';
} else {
$value = (string) $value;
}
}

if (str_contains($value, '/')) {

if (preg_match(self::PARSE_REGEXP_RATIONAL, $value, $matches, PREG_UNMATCHED_AS_NULL) !== 1) {
throw NumberFormatException::invalidFormat($value);
}

$sign = $matches['sign'];
$numerator = $matches['numerator'];
$denominator = $matches['denominator'];

$numerator = self::cleanUp($sign, $numerator);
$denominator = self::cleanUp(null, $denominator);

if ($denominator === '0') {
throw DivisionByZeroException::denominatorMustNotBeZero();
}

return new BigRational(
new BigInteger($numerator),
new BigInteger($denominator),
false,
);
} else {

if (preg_match(self::PARSE_REGEXP_NUMERICAL, $value, $matches, PREG_UNMATCHED_AS_NULL) !== 1) {
throw NumberFormatException::invalidFormat($value);
}

$sign = $matches['sign'];
$point = $matches['point'];
$integral = $matches['integral'];
$fractional = $matches['fractional'];
$exponent = $matches['exponent'];

if ($integral === null && $fractional === null) {
throw NumberFormatException::invalidFormat($value);
}

if ($integral === null) {
$integral = '0';
}

if ($point !== null || $exponent !== null) {
$fractional ??= '';

if ($exponent !== null) {
if ($exponent[0] === '-') {
$exponent = ltrim(substr($exponent, 1), '0') ?: '0';
$exponent = filter_var($exponent, FILTER_VALIDATE_INT);
if ($exponent !== false) {
$exponent = -$exponent;
}
} else {
if ($exponent[0] === '+') {
$exponent = substr($exponent, 1);
}
$exponent = ltrim($exponent, '0') ?: '0';
$exponent = filter_var($exponent, FILTER_VALIDATE_INT);
}
} else {
$exponent = 0;
}

if ($exponent === false) {
throw new NumberFormatException('Exponent too large.');
}

$unscaledValue = self::cleanUp($sign, $integral . $fractional);

$scale = strlen($fractional) - $exponent;

if ($scale < 0) {
if ($unscaledValue !== '0') {
$unscaledValue .= str_repeat('0', -$scale);
}
$scale = 0;
}

return new BigDecimal($unscaledValue, $scale);
}

$integral = self::cleanUp($sign, $integral);

return new BigInteger($integral);
}
}

/**
@pure





*/
private static function cleanUp(string|null $sign, string $number): string
{
$number = ltrim($number, '0');

if ($number === '') {
return '0';
}

return $sign === '-' ? '-' . $number : $number;
}

/**
@pure


*/
private static function add(BigNumber $a, BigNumber $b): BigNumber
{
if ($a instanceof BigRational) {
return $a->plus($b);
}

if ($b instanceof BigRational) {
return $b->plus($a);
}

if ($a instanceof BigDecimal) {
return $a->plus($b);
}

if ($b instanceof BigDecimal) {
return $b->plus($a);
}

return $a->plus($b);
}
}
