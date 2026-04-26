<?php

declare(strict_types=1);

namespace Brick\Math;

use Brick\Math\Exception\DivisionByZeroException;
use Brick\Math\Exception\IntegerOverflowException;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NegativeNumberException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\Internal\Calculator;
use Brick\Math\Internal\CalculatorRegistry;
use InvalidArgumentException;
use LogicException;
use Override;

use function assert;
use function bin2hex;
use function chr;
use function count_chars;
use function filter_var;
use function func_num_args;
use function hex2bin;
use function in_array;
use function intdiv;
use function ltrim;
use function ord;
use function preg_match;
use function preg_quote;
use function random_bytes;
use function sprintf;
use function str_repeat;
use function strlen;
use function strtolower;
use function substr;
use function trigger_error;

use const E_USER_DEPRECATED;
use const FILTER_VALIDATE_INT;






final readonly class BigInteger extends BigNumber
{






private string $value;

/**
@pure




*/
protected function __construct(string $value)
{
$this->value = $value;
}

/**
@pure
















*/
public static function fromBase(string $number, int $base): BigInteger
{
if ($number === '') {
throw new NumberFormatException('The number must not be empty.');
}

if ($base < 2 || $base > 36) {
throw new InvalidArgumentException(sprintf('Base %d is not in range 2 to 36.', $base));
}

if ($number[0] === '-') {
$sign = '-';
$number = substr($number, 1);
} elseif ($number[0] === '+') {
$sign = '';
$number = substr($number, 1);
} else {
$sign = '';
}

if ($number === '') {
throw new NumberFormatException('The number must not be empty.');
}

$number = ltrim($number, '0');

if ($number === '') {

return BigInteger::zero();
}

if ($number === '1') {

return new BigInteger($sign . '1');
}

$pattern = '/[^' . substr(Calculator::ALPHABET, 0, $base) . ']/';

if (preg_match($pattern, strtolower($number), $matches) === 1) {
throw new NumberFormatException(sprintf('"%s" is not a valid character in base %d.', $matches[0], $base));
}

if ($base === 10) {

return new BigInteger($sign . $number);
}

$result = CalculatorRegistry::get()->fromBase($number, $base);

return new BigInteger($sign . $result);
}

/**
@pure













*/
public static function fromArbitraryBase(string $number, string $alphabet): BigInteger
{
if ($number === '') {
throw new NumberFormatException('The number must not be empty.');
}

$base = strlen($alphabet);

if ($base < 2) {
throw new InvalidArgumentException('The alphabet must contain at least 2 chars.');
}

if (strlen(count_chars($alphabet, 3)) !== $base) {
throw new InvalidArgumentException('The alphabet must not contain duplicate chars.');
}

$pattern = '/[^' . preg_quote($alphabet, '/') . ']/';

if (preg_match($pattern, $number, $matches) === 1) {
throw NumberFormatException::charNotInAlphabet($matches[0]);
}

$number = CalculatorRegistry::get()->fromArbitraryBase($number, $alphabet, $base);

return new BigInteger($number);
}

/**
@pure
















*/
public static function fromBytes(string $value, bool $signed = true): BigInteger
{
if ($value === '') {
throw new NumberFormatException('The byte string must not be empty.');
}

$twosComplement = false;

if ($signed) {
$x = ord($value[0]);

if (($twosComplement = ($x >= 0x80))) {
$value = ~$value;
}
}

$number = self::fromBase(bin2hex($value), 16);

if ($twosComplement) {
return $number->plus(1)->negated();
}

return $number;
}













public static function randomBits(int $numBits, ?callable $randomBytesGenerator = null): BigInteger
{
if ($numBits < 0) {
throw new InvalidArgumentException('The number of bits must not be negative.');
}

if ($numBits === 0) {
return BigInteger::zero();
}

if ($randomBytesGenerator === null) {
$randomBytesGenerator = random_bytes(...);
}


$byteLength = intdiv($numBits - 1, 8) + 1;

$extraBits = ($byteLength * 8 - $numBits);
$bitmask = chr(0xFF >> $extraBits);

$randomBytes = $randomBytesGenerator($byteLength);
$randomBytes[0] = $randomBytes[0] & $bitmask;

return self::fromBytes($randomBytes, false);
}















public static function randomRange(
BigNumber|int|float|string $min,
BigNumber|int|float|string $max,
?callable $randomBytesGenerator = null,
): BigInteger {
$min = BigInteger::of($min);
$max = BigInteger::of($max);

if ($min->isGreaterThan($max)) {
throw new MathException('$min must be less than or equal to $max.');
}

if ($min->isEqualTo($max)) {
return $min;
}

$diff = $max->minus($min);
$bitLength = $diff->getBitLength();


do {
$randomNumber = self::randomBits($bitLength, $randomBytesGenerator);
} while ($randomNumber->isGreaterThan($diff));

return $randomNumber->plus($min);
}

/**
@pure


*/
public static function zero(): BigInteger
{

static $zero;

if ($zero === null) {
$zero = new BigInteger('0');
}

return $zero;
}

/**
@pure


*/
public static function one(): BigInteger
{

static $one;

if ($one === null) {
$one = new BigInteger('1');
}

return $one;
}

/**
@pure


*/
public static function ten(): BigInteger
{

static $ten;

if ($ten === null) {
$ten = new BigInteger('10');
}

return $ten;
}

/**
@pure









*/
public static function gcdAll(BigNumber|int|float|string $a, BigNumber|int|float|string ...$n): BigInteger
{
$result = BigInteger::of($a)->abs();

foreach ($n as $next) {
$result = $result->gcd(BigInteger::of($next));

if ($result->isEqualTo(1)) {
return $result;
}
}

return $result;
}

/**
@pure









*/
public static function lcmAll(BigNumber|int|float|string $a, BigNumber|int|float|string ...$n): BigInteger
{
$result = BigInteger::of($a)->abs();

foreach ($n as $next) {
$result = $result->lcm(BigInteger::of($next));

if ($result->isZero()) {
return $result;
}
}

return $result;
}







public static function gcdMultiple(BigNumber|int|float|string $a, BigNumber|int|float|string ...$n): BigInteger
{
trigger_error(
'BigInteger::gcdMultiple() is deprecated and will be removed in version 0.15. Use gcdAll() instead.',
E_USER_DEPRECATED,
);

return self::gcdAll($a, ...$n);
}

/**
@pure






*/
public function plus(BigNumber|int|float|string $that): BigInteger
{
$that = BigInteger::of($that);

if ($that->value === '0') {
return $this;
}

if ($this->value === '0') {
return $that;
}

$value = CalculatorRegistry::get()->add($this->value, $that->value);

return new BigInteger($value);
}

/**
@pure






*/
public function minus(BigNumber|int|float|string $that): BigInteger
{
$that = BigInteger::of($that);

if ($that->value === '0') {
return $this;
}

$value = CalculatorRegistry::get()->sub($this->value, $that->value);

return new BigInteger($value);
}

/**
@pure






*/
public function multipliedBy(BigNumber|int|float|string $that): BigInteger
{
$that = BigInteger::of($that);

if ($that->value === '1') {
return $this;
}

if ($this->value === '1') {
return $that;
}

$value = CalculatorRegistry::get()->mul($this->value, $that->value);

return new BigInteger($value);
}

/**
@pure









*/
public function dividedBy(BigNumber|int|float|string $that, RoundingMode $roundingMode = RoundingMode::Unnecessary): BigInteger
{
$that = BigInteger::of($that);

if ($that->value === '1') {
return $this;
}

if ($that->value === '0') {
throw DivisionByZeroException::divisionByZero();
}

$result = CalculatorRegistry::get()->divRound($this->value, $that->value, $roundingMode);

return new BigInteger($result);
}

/**
@pure




*/
public function power(int $exponent): BigInteger
{
if ($exponent === 0) {
return BigInteger::one();
}

if ($exponent === 1) {
return $this;
}

if ($exponent < 0 || $exponent > Calculator::MAX_POWER) {
throw new InvalidArgumentException(sprintf(
'The exponent %d is not in the range 0 to %d.',
$exponent,
Calculator::MAX_POWER,
));
}

return new BigInteger(CalculatorRegistry::get()->pow($this->value, $exponent));
}

/**
@pure














*/
public function quotient(BigNumber|int|float|string $that): BigInteger
{
$that = BigInteger::of($that);

if ($that->value === '1') {
return $this;
}

if ($that->value === '0') {
throw DivisionByZeroException::divisionByZero();
}

$quotient = CalculatorRegistry::get()->divQ($this->value, $that->value);

return new BigInteger($quotient);
}

/**
@pure
















*/
public function remainder(BigNumber|int|float|string $that): BigInteger
{
$that = BigInteger::of($that);

if ($that->value === '1') {
return BigInteger::zero();
}

if ($that->value === '0') {
throw DivisionByZeroException::divisionByZero();
}

$remainder = CalculatorRegistry::get()->divR($this->value, $that->value);

return new BigInteger($remainder);
}

/**
@pure
















*/
public function quotientAndRemainder(BigNumber|int|float|string $that): array
{
$that = BigInteger::of($that);

if ($that->value === '0') {
throw DivisionByZeroException::divisionByZero();
}

[$quotient, $remainder] = CalculatorRegistry::get()->divQR($this->value, $that->value);

return [
new BigInteger($quotient),
new BigInteger($remainder),
];
}

/**
@pure












*/
public function mod(BigNumber|int|float|string $that): BigInteger
{
$that = BigInteger::of($that);

if ($that->isZero()) {
throw DivisionByZeroException::modulusMustNotBeZero();
}

if ($that->isNegative()) {

trigger_error(
'Passing a negative modulus to BigInteger::mod() is deprecated and will throw a NegativeNumberException in 0.15.',
E_USER_DEPRECATED,
);
}

$value = CalculatorRegistry::get()->mod($this->value, $that->value);

return new BigInteger($value);
}

/**
@pure










*/
public function modInverse(BigNumber|int|float|string $m): BigInteger
{
$m = BigInteger::of($m);

if ($m->value === '0') {
throw DivisionByZeroException::modulusMustNotBeZero();
}

if ($m->isNegative()) {
throw new NegativeNumberException('Modulus must not be negative.');
}

if ($m->value === '1') {
return BigInteger::zero();
}

$value = CalculatorRegistry::get()->modInverse($this->value, $m->value);

if ($value === null) {
throw new MathException('Unable to compute the modInverse for the given modulus.');
}

return new BigInteger($value);
}

/**
@pure











*/
public function modPow(BigNumber|int|float|string $exp, BigNumber|int|float|string $mod): BigInteger
{
$exp = BigInteger::of($exp);
$mod = BigInteger::of($mod);

if ($exp->isNegative()) {
throw new NegativeNumberException('The exponent cannot be negative.');
}

if ($mod->isNegative()) {
throw new NegativeNumberException('The modulus cannot be negative.');
}

if ($mod->isZero()) {
throw DivisionByZeroException::modulusMustNotBeZero();
}

$result = CalculatorRegistry::get()->modPow($this->value, $exp->value, $mod->value);

return new BigInteger($result);
}

/**
@pure








*/
public function gcd(BigNumber|int|float|string $that): BigInteger
{
$that = BigInteger::of($that);

if ($that->value === '0' && $this->value[0] !== '-') {
return $this;
}

if ($this->value === '0' && $that->value[0] !== '-') {
return $that;
}

$value = CalculatorRegistry::get()->gcd($this->value, $that->value);

return new BigInteger($value);
}

/**
@pure








*/
public function lcm(BigNumber|int|float|string $that): BigInteger
{
$that = BigInteger::of($that);

if ($this->isZero() || $that->isZero()) {
return BigInteger::zero();
}

$value = CalculatorRegistry::get()->lcm($this->value, $that->value);

return new BigInteger($value);
}

/**
@pure










*/
public function sqrt(RoundingMode $roundingMode = RoundingMode::Down): BigInteger
{
if (func_num_args() === 0) {

trigger_error(
'The default rounding mode of BigInteger::sqrt() will change from Down to Unnecessary in version 0.15. ' .
'Pass a rounding mode explicitly to avoid this breaking change.',
E_USER_DEPRECATED,
);
}

if ($this->value[0] === '-') {
throw new NegativeNumberException('Cannot calculate the square root of a negative number.');
}

$calculator = CalculatorRegistry::get();

$sqrt = $calculator->sqrt($this->value);


if ($roundingMode === RoundingMode::Down || $roundingMode === RoundingMode::Floor) {
return new BigInteger($sqrt);
}


$s2 = $calculator->mul($sqrt, $sqrt);
$remainder = $calculator->sub($this->value, $s2);

if ($remainder === '0') {

return new BigInteger($sqrt);
}


if ($roundingMode === RoundingMode::Unnecessary) {
throw RoundingNecessaryException::roundingNecessary();
}


if ($roundingMode === RoundingMode::Up || $roundingMode === RoundingMode::Ceiling) {
return new BigInteger($calculator->add($sqrt, '1'));
}




$twoRemainder = $calculator->mul($remainder, '2');
$threshold = $calculator->add($calculator->mul($sqrt, '2'), '1');
$cmp = $calculator->cmp($twoRemainder, $threshold);







if ($cmp > 0) {
$sqrt = $calculator->add($sqrt, '1');
}

return new BigInteger($sqrt);
}

#[Override]
public function negated(): static
{
return new BigInteger(CalculatorRegistry::get()->neg($this->value));
}

/**
@pure








*/
public function and(BigNumber|int|float|string $that): BigInteger
{
$that = BigInteger::of($that);

return new BigInteger(CalculatorRegistry::get()->and($this->value, $that->value));
}

/**
@pure








*/
public function or(BigNumber|int|float|string $that): BigInteger
{
$that = BigInteger::of($that);

return new BigInteger(CalculatorRegistry::get()->or($this->value, $that->value));
}

/**
@pure








*/
public function xor(BigNumber|int|float|string $that): BigInteger
{
$that = BigInteger::of($that);

return new BigInteger(CalculatorRegistry::get()->xor($this->value, $that->value));
}

/**
@pure


*/
public function not(): BigInteger
{
return $this->negated()->minus(1);
}

/**
@pure




*/
public function shiftedLeft(int $distance): BigInteger
{
if ($distance === 0) {
return $this;
}

if ($distance < 0) {
return $this->shiftedRight(-$distance);
}

return $this->multipliedBy(BigInteger::of(2)->power($distance));
}

/**
@pure




*/
public function shiftedRight(int $distance): BigInteger
{
if ($distance === 0) {
return $this;
}

if ($distance < 0) {
return $this->shiftedLeft(-$distance);
}

$operand = BigInteger::of(2)->power($distance);

if ($this->isPositiveOrZero()) {
return $this->quotient($operand);
}

return $this->dividedBy($operand, RoundingMode::Up);
}

/**
@pure





*/
public function getBitLength(): int
{
if ($this->value === '0') {
return 0;
}

if ($this->isNegative()) {
return $this->abs()->minus(1)->getBitLength();
}

return strlen($this->toBase(2));
}

/**
@pure




*/
public function getLowestSetBit(): int
{
$n = $this;
$bitLength = $this->getBitLength();

for ($i = 0; $i <= $bitLength; $i++) {
if ($n->isOdd()) {
return $i;
}

$n = $n->shiftedRight(1);
}

return -1;
}

/**
@pure








*/
public function isBitSet(int $n): bool
{
if ($n < 0) {
throw new InvalidArgumentException('The bit to test cannot be negative.');
}

return $this->shiftedRight($n)->isOdd();
}

/**
@pure


*/
public function isEven(): bool
{
return in_array($this->value[-1], ['0', '2', '4', '6', '8'], true);
}

/**
@pure


*/
public function isOdd(): bool
{
return in_array($this->value[-1], ['1', '3', '5', '7', '9'], true);
}












public function testBit(int $n): bool
{
trigger_error(
'The BigInteger::testBit() method is deprecated, use isBitSet() instead.',
E_USER_DEPRECATED,
);

return $this->isBitSet($n);
}

#[Override]
public function compareTo(BigNumber|int|float|string $that): int
{
$that = BigNumber::of($that);

if ($that instanceof BigInteger) {
return CalculatorRegistry::get()->cmp($this->value, $that->value);
}

return -$that->compareTo($this);
}

#[Override]
public function getSign(): int
{
return ($this->value === '0') ? 0 : (($this->value[0] === '-') ? -1 : 1);
}

#[Override]
public function toBigInteger(): BigInteger
{
return $this;
}

#[Override]
public function toBigDecimal(): BigDecimal
{
return self::newBigDecimal($this->value);
}

#[Override]
public function toBigRational(): BigRational
{
return self::newBigRational($this, BigInteger::one(), false);
}

#[Override]
public function toScale(int $scale, RoundingMode $roundingMode = RoundingMode::Unnecessary): BigDecimal
{
return $this->toBigDecimal()->toScale($scale, $roundingMode);
}

#[Override]
public function toInt(): int
{
$intValue = filter_var($this->value, FILTER_VALIDATE_INT);

if ($intValue === false) {
throw IntegerOverflowException::toIntOverflow($this);
}

return $intValue;
}

#[Override]
public function toFloat(): float
{
return (float) $this->value;
}

/**
@pure






*/
public function toBase(int $base): string
{
if ($base === 10) {
return $this->value;
}

if ($base < 2 || $base > 36) {
throw new InvalidArgumentException(sprintf('Base %d is out of range [2, 36]', $base));
}

return CalculatorRegistry::get()->toBase($this->value, $base);
}

/**
@pure













*/
public function toArbitraryBase(string $alphabet): string
{
$base = strlen($alphabet);

if ($base < 2) {
throw new InvalidArgumentException('The alphabet must contain at least 2 chars.');
}

if (strlen(count_chars($alphabet, 3)) !== $base) {
throw new InvalidArgumentException('The alphabet must not contain duplicate chars.');
}

if ($this->value[0] === '-') {
throw new NegativeNumberException(__FUNCTION__ . '() does not support negative numbers.');
}

return CalculatorRegistry::get()->toArbitraryBase($this->value, $alphabet, $base);
}

/**
@pure

















*/
public function toBytes(bool $signed = true): string
{
if (! $signed && $this->isNegative()) {
throw new NegativeNumberException('Cannot convert a negative number to a byte string when $signed is false.');
}

$hex = $this->abs()->toBase(16);

if (strlen($hex) % 2 !== 0) {
$hex = '0' . $hex;
}

$baseHexLength = strlen($hex);

if ($signed) {
if ($this->isNegative()) {
$bin = hex2bin($hex);
assert($bin !== false);

$hex = bin2hex(~$bin);
$hex = self::fromBase($hex, 16)->plus(1)->toBase(16);

$hexLength = strlen($hex);

if ($hexLength < $baseHexLength) {
$hex = str_repeat('0', $baseHexLength - $hexLength) . $hex;
}

if ($hex[0] < '8') {
$hex = 'FF' . $hex;
}
} else {
if ($hex[0] >= '8') {
$hex = '00' . $hex;
}
}
}

$result = hex2bin($hex);
assert($result !== false);

return $result;
}




#[Override]
public function toString(): string
{

return $this->value;
}








public function __serialize(): array
{
return ['value' => $this->value];
}










public function __unserialize(array $data): void
{
/**
@phpstan-ignore */
if (isset($this->value)) {
throw new LogicException('__unserialize() is an internal function, it must not be called directly.');
}

/**
@phpstan-ignore */
$this->value = $data['value'];
}

#[Override]
protected static function from(BigNumber $number): static
{
return $number->toBigInteger();
}
}
