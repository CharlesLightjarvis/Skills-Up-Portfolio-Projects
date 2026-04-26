<?php

declare(strict_types=1);

namespace Brick\Math;

use Brick\Math\Exception\DivisionByZeroException;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use InvalidArgumentException;
use LogicException;
use Override;

use function is_finite;
use function max;
use function min;
use function strlen;
use function substr;
use function trigger_error;

use const E_USER_DEPRECATED;









final readonly class BigRational extends BigNumber
{



private BigInteger $numerator;




private BigInteger $denominator;

/**
@pure








*/
protected function __construct(BigInteger $numerator, BigInteger $denominator, bool $checkDenominator)
{
if ($checkDenominator) {
if ($denominator->isZero()) {
throw DivisionByZeroException::denominatorMustNotBeZero();
}

if ($denominator->isNegative()) {
$numerator = $numerator->negated();
$denominator = $denominator->negated();
}
}

$this->numerator = $numerator;
$this->denominator = $denominator;
}
















public static function nd(
BigNumber|int|float|string $numerator,
BigNumber|int|float|string $denominator,
): BigRational {
trigger_error(
'The BigRational::nd() method is deprecated, use BigRational::ofFraction() instead.',
E_USER_DEPRECATED,
);

return self::ofFraction($numerator, $denominator);
}

/**
@pure











*/
public static function ofFraction(
BigNumber|int|float|string $numerator,
BigNumber|int|float|string $denominator,
): BigRational {
$numerator = BigInteger::of($numerator);
$denominator = BigInteger::of($denominator);

return new BigRational($numerator, $denominator, true);
}

/**
@pure


*/
public static function zero(): BigRational
{

static $zero;

if ($zero === null) {
$zero = new BigRational(BigInteger::zero(), BigInteger::one(), false);
}

return $zero;
}

/**
@pure


*/
public static function one(): BigRational
{

static $one;

if ($one === null) {
$one = new BigRational(BigInteger::one(), BigInteger::one(), false);
}

return $one;
}

/**
@pure


*/
public static function ten(): BigRational
{

static $ten;

if ($ten === null) {
$ten = new BigRational(BigInteger::ten(), BigInteger::one(), false);
}

return $ten;
}

/**
@pure
*/
public function getNumerator(): BigInteger
{
return $this->numerator;
}

/**
@pure
*/
public function getDenominator(): BigInteger
{
return $this->denominator;
}






public function quotient(): BigInteger
{
trigger_error(
'BigRational::quotient() is deprecated and will be removed in 0.15. Use getIntegralPart() instead.',
E_USER_DEPRECATED,
);

return $this->numerator->quotient($this->denominator);
}






public function remainder(): BigInteger
{
trigger_error(
'BigRational::remainder() is deprecated and will be removed in 0.15. Use `$number->getNumerator()->remainder($number->getDenominator())` instead.',
E_USER_DEPRECATED,
);

return $this->numerator->remainder($this->denominator);
}








public function quotientAndRemainder(): array
{
trigger_error(
'BigRational::quotientAndRemainder() is deprecated and will be removed in 0.15. Use `$number->getNumerator()->quotientAndRemainder($number->getDenominator())` instead.',
E_USER_DEPRECATED,
);

return $this->numerator->quotientAndRemainder($this->denominator);
}

/**
@pure









*/
public function getIntegralPart(): BigInteger
{
return $this->numerator->quotient($this->denominator);
}

/**
@pure









*/
public function getFractionalPart(): BigRational
{
return new BigRational($this->numerator->remainder($this->denominator), $this->denominator, false);
}

/**
@pure






*/
public function plus(BigNumber|int|float|string $that): BigRational
{
$that = BigRational::of($that);

$numerator = $this->numerator->multipliedBy($that->denominator);
$numerator = $numerator->plus($that->numerator->multipliedBy($this->denominator));
$denominator = $this->denominator->multipliedBy($that->denominator);

return new BigRational($numerator, $denominator, false);
}

/**
@pure






*/
public function minus(BigNumber|int|float|string $that): BigRational
{
$that = BigRational::of($that);

$numerator = $this->numerator->multipliedBy($that->denominator);
$numerator = $numerator->minus($that->numerator->multipliedBy($this->denominator));
$denominator = $this->denominator->multipliedBy($that->denominator);

return new BigRational($numerator, $denominator, false);
}

/**
@pure






*/
public function multipliedBy(BigNumber|int|float|string $that): BigRational
{
$that = BigRational::of($that);

$numerator = $this->numerator->multipliedBy($that->numerator);
$denominator = $this->denominator->multipliedBy($that->denominator);

return new BigRational($numerator, $denominator, false);
}

/**
@pure







*/
public function dividedBy(BigNumber|int|float|string $that): BigRational
{
$that = BigRational::of($that);

if ($that->isZero()) {
throw DivisionByZeroException::divisionByZero();
}

$numerator = $this->numerator->multipliedBy($that->denominator);
$denominator = $this->denominator->multipliedBy($that->numerator);

return new BigRational($numerator, $denominator, true);
}

/**
@pure




*/
public function power(int $exponent): BigRational
{
if ($exponent === 0) {
return BigRational::one();
}

if ($exponent === 1) {
return $this;
}

return new BigRational(
$this->numerator->power($exponent),
$this->denominator->power($exponent),
false,
);
}

/**
@pure






*/
public function reciprocal(): BigRational
{
return new BigRational($this->denominator, $this->numerator, true);
}

#[Override]
public function negated(): static
{
return new BigRational($this->numerator->negated(), $this->denominator, false);
}

/**
@pure


*/
public function simplified(): BigRational
{
$gcd = $this->numerator->gcd($this->denominator);

$numerator = $this->numerator->quotient($gcd);
$denominator = $this->denominator->quotient($gcd);

return new BigRational($numerator, $denominator, false);
}

#[Override]
public function compareTo(BigNumber|int|float|string $that): int
{
$that = BigRational::of($that);

if ($this->denominator->isEqualTo($that->denominator)) {
return $this->numerator->compareTo($that->numerator);
}

return $this->numerator
->multipliedBy($that->denominator)
->compareTo($that->numerator->multipliedBy($this->denominator));
}

#[Override]
public function getSign(): int
{
return $this->numerator->getSign();
}

#[Override]
public function toBigInteger(): BigInteger
{
$simplified = $this->simplified();

if (! $simplified->denominator->isEqualTo(1)) {
throw new RoundingNecessaryException('This rational number cannot be represented as an integer value without rounding.');
}

return $simplified->numerator;
}

#[Override]
public function toBigDecimal(): BigDecimal
{
return $this->numerator->toBigDecimal()->dividedByExact($this->denominator);
}

#[Override]
public function toBigRational(): BigRational
{
return $this;
}

#[Override]
public function toScale(int $scale, RoundingMode $roundingMode = RoundingMode::Unnecessary): BigDecimal
{
return $this->numerator->toBigDecimal()->dividedBy($this->denominator, $scale, $roundingMode);
}

#[Override]
public function toInt(): int
{
return $this->toBigInteger()->toInt();
}

#[Override]
public function toFloat(): float
{
$simplified = $this->simplified();
$numeratorFloat = $simplified->numerator->toFloat();
$denominatorFloat = $simplified->denominator->toFloat();

if (is_finite($numeratorFloat) && is_finite($denominatorFloat)) {
return $numeratorFloat / $denominatorFloat;
}






$magnitude = strlen($simplified->numerator->abs()->toString()) - strlen($simplified->denominator->toString());
$scale = min(350, max(0, 20 - $magnitude));

return $simplified->numerator
->toBigDecimal()
->dividedBy($simplified->denominator, $scale, RoundingMode::HalfEven)
->toFloat();
}

#[Override]
public function toString(): string
{
$numerator = $this->numerator->toString();
$denominator = $this->denominator->toString();

if ($denominator === '1') {
return $numerator;
}

return $numerator . '/' . $denominator;
}

/**
@pure













*/
public function toRepeatingDecimalString(): string
{
if ($this->numerator->isZero()) {
return '0';
}

$sign = $this->numerator->isNegative() ? '-' : '';
$numerator = $this->numerator->abs();
$denominator = $this->denominator;

$integral = $numerator->quotient($denominator);
$remainder = $numerator->remainder($denominator);

$integralString = $integral->toString();

if ($remainder->isZero()) {
return $sign . $integralString;
}

$digits = '';
$remainderPositions = [];
$index = 0;

while (! $remainder->isZero()) {
$remainderString = $remainder->toString();

if (isset($remainderPositions[$remainderString])) {
$repeatIndex = $remainderPositions[$remainderString];
$nonRepeating = substr($digits, 0, $repeatIndex);
$repeating = substr($digits, $repeatIndex);

return $sign . $integralString . '.' . $nonRepeating . '(' . $repeating . ')';
}

$remainderPositions[$remainderString] = $index;
$remainder = $remainder->multipliedBy(10);

$digits .= $remainder->quotient($denominator)->toString();
$remainder = $remainder->remainder($denominator);
$index++;
}

return $sign . $integralString . '.' . $digits;
}








public function __serialize(): array
{
return ['numerator' => $this->numerator, 'denominator' => $this->denominator];
}










public function __unserialize(array $data): void
{
/**
@phpstan-ignore */
if (isset($this->numerator)) {
throw new LogicException('__unserialize() is an internal function, it must not be called directly.');
}

/**
@phpstan-ignore */
$this->numerator = $data['numerator'];
$this->denominator = $data['denominator'];
}

#[Override]
protected static function from(BigNumber $number): static
{
return $number->toBigRational();
}
}
