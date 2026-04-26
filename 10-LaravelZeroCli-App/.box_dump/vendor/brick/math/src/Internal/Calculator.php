<?php

declare(strict_types=1);

namespace Brick\Math\Internal;

use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;

use function chr;
use function ltrim;
use function ord;
use function str_repeat;
use function strlen;
use function strpos;
use function strrev;
use function strtolower;
use function substr;












abstract readonly class Calculator
{



public const MAX_POWER = 1_000_000;




public const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyz';

/**
@pure


*/
final public function abs(string $n): string
{
return ($n[0] === '-') ? substr($n, 1) : $n;
}

/**
@pure


*/
final public function neg(string $n): string
{
if ($n === '0') {
return '0';
}

if ($n[0] === '-') {
return substr($n, 1);
}

return '-' . $n;
}

/**
@pure






*/
final public function cmp(string $a, string $b): int
{
[$aNeg, $bNeg, $aDig, $bDig] = $this->init($a, $b);

if ($aNeg && ! $bNeg) {
return -1;
}

if ($bNeg && ! $aNeg) {
return 1;
}

$aLen = strlen($aDig);
$bLen = strlen($bDig);

if ($aLen < $bLen) {
$result = -1;
} elseif ($aLen > $bLen) {
$result = 1;
} else {
$result = $aDig <=> $bDig;
}

return $aNeg ? -$result : $result;
}

/**
@pure


*/
abstract public function add(string $a, string $b): string;

/**
@pure


*/
abstract public function sub(string $a, string $b): string;

/**
@pure


*/
abstract public function mul(string $a, string $b): string;

/**
@pure







*/
abstract public function divQ(string $a, string $b): string;

/**
@pure







*/
abstract public function divR(string $a, string $b): string;

/**
@pure







*/
abstract public function divQR(string $a, string $b): array;

/**
@pure







*/
abstract public function pow(string $a, int $e): string;

/**
@pure


*/
public function mod(string $a, string $b): string
{
return $this->divR($this->add($this->divR($a, $b), $b), $b);
}

/**
@pure








*/
public function modInverse(string $x, string $m): ?string
{
if ($m === '1') {
return '0';
}

$modVal = $x;

if ($x[0] === '-' || ($this->cmp($this->abs($x), $m) >= 0)) {
$modVal = $this->mod($x, $m);
}

[$g, $x] = $this->gcdExtended($modVal, $m);

if ($g !== '1') {
return null;
}

return $this->mod($this->add($this->mod($x, $m), $m), $m);
}

/**
@pure






*/
abstract public function modPow(string $base, string $exp, string $mod): string;

/**
@pure







*/
public function gcd(string $a, string $b): string
{
if ($a === '0') {
return $this->abs($b);
}

if ($b === '0') {
return $this->abs($a);
}

return $this->gcd($b, $this->divR($a, $b));
}

/**
@pure







*/
public function lcm(string $a, string $b): string
{
if ($a === '0' || $b === '0') {
return '0';
}

return $this->divQ($this->abs($this->mul($a, $b)), $this->gcd($a, $b));
}

/**
@pure





*/
abstract public function sqrt(string $n): string;

/**
@pure










*/
public function fromBase(string $number, int $base): string
{
return $this->fromArbitraryBase(strtolower($number), self::ALPHABET, $base);
}

/**
@pure










*/
public function toBase(string $number, int $base): string
{
$negative = ($number[0] === '-');

if ($negative) {
$number = substr($number, 1);
}

$number = $this->toArbitraryBase($number, self::ALPHABET, $base);

if ($negative) {
return '-' . $number;
}

return $number;
}

/**
@pure









*/
final public function fromArbitraryBase(string $number, string $alphabet, int $base): string
{

$number = ltrim($number, $alphabet[0]);

if ($number === '') {
return '0';
}


if ($number === $alphabet[1]) {
return '1';
}

$result = '0';
$power = '1';

$base = (string) $base;

for ($i = strlen($number) - 1; $i >= 0; $i--) {
$index = strpos($alphabet, $number[$i]);

if ($index !== 0) {
$result = $this->add(
$result,
($index === 1) ? $power : $this->mul($power, (string) $index),
);
}

if ($i !== 0) {
$power = $this->mul($power, $base);
}
}

return $result;
}

/**
@pure








*/
final public function toArbitraryBase(string $number, string $alphabet, int $base): string
{
if ($number === '0') {
return $alphabet[0];
}

$base = (string) $base;
$result = '';

while ($number !== '0') {
[$number, $remainder] = $this->divQR($number, $base);
$remainder = (int) $remainder;

$result .= $alphabet[$remainder];
}

return strrev($result);
}

/**
@pure










*/
final public function divRound(string $a, string $b, RoundingMode $roundingMode): string
{
[$quotient, $remainder] = $this->divQR($a, $b);

$hasDiscardedFraction = ($remainder !== '0');
$isPositiveOrZero = ($a[0] === '-') === ($b[0] === '-');

$discardedFractionSign = function () use ($remainder, $b): int {
$r = $this->abs($this->mul($remainder, '2'));
$b = $this->abs($b);

return $this->cmp($r, $b);
};

$increment = false;

switch ($roundingMode) {
case RoundingMode::Unnecessary:
if ($hasDiscardedFraction) {
throw RoundingNecessaryException::roundingNecessary();
}

break;

case RoundingMode::Up:
$increment = $hasDiscardedFraction;

break;

case RoundingMode::Down:
break;

case RoundingMode::Ceiling:
$increment = $hasDiscardedFraction && $isPositiveOrZero;

break;

case RoundingMode::Floor:
$increment = $hasDiscardedFraction && ! $isPositiveOrZero;

break;

case RoundingMode::HalfUp:
$increment = $discardedFractionSign() >= 0;

break;

case RoundingMode::HalfDown:
$increment = $discardedFractionSign() > 0;

break;

case RoundingMode::HalfCeiling:
$increment = $isPositiveOrZero ? $discardedFractionSign() >= 0 : $discardedFractionSign() > 0;

break;

case RoundingMode::HalfFloor:
$increment = $isPositiveOrZero ? $discardedFractionSign() > 0 : $discardedFractionSign() >= 0;

break;

case RoundingMode::HalfEven:
$lastDigit = (int) $quotient[-1];
$lastDigitIsEven = ($lastDigit % 2 === 0);
$increment = $lastDigitIsEven ? $discardedFractionSign() > 0 : $discardedFractionSign() >= 0;

break;
}

if ($increment) {
return $this->add($quotient, $isPositiveOrZero ? '1' : '-1');
}

return $quotient;
}

/**
@pure





*/
public function and(string $a, string $b): string
{
return $this->bitwise('and', $a, $b);
}

/**
@pure





*/
public function or(string $a, string $b): string
{
return $this->bitwise('or', $a, $b);
}

/**
@pure





*/
public function xor(string $a, string $b): string
{
return $this->bitwise('xor', $a, $b);
}

/**
@pure




*/
final protected function init(string $a, string $b): array
{
return [
$aNeg = ($a[0] === '-'),
$bNeg = ($b[0] === '-'),

$aNeg ? substr($a, 1) : $a,
$bNeg ? substr($b, 1) : $b,
];
}

/**
@pure


*/
private function gcdExtended(string $a, string $b): array
{
if ($a === '0') {
return [$b, '0', '1'];
}

[$gcd, $x1, $y1] = $this->gcdExtended($this->mod($b, $a), $a);

$x = $this->sub($y1, $this->mul($this->divQ($b, $a), $x1));
$y = $x1;

return [$gcd, $x, $y];
}

/**
@pure






*/
private function bitwise(string $operator, string $a, string $b): string
{
[$aNeg, $bNeg, $aDig, $bDig] = $this->init($a, $b);

$aBin = $this->toBinary($aDig);
$bBin = $this->toBinary($bDig);

$aLen = strlen($aBin);
$bLen = strlen($bBin);

if ($aLen > $bLen) {
$bBin = str_repeat("\x00", $aLen - $bLen) . $bBin;
} elseif ($bLen > $aLen) {
$aBin = str_repeat("\x00", $bLen - $aLen) . $aBin;
}

if ($aNeg) {
$aBin = $this->twosComplement($aBin);
}
if ($bNeg) {
$bBin = $this->twosComplement($bBin);
}

$value = match ($operator) {
'and' => $aBin & $bBin,
'or' => $aBin | $bBin,
'xor' => $aBin ^ $bBin,
};

$negative = match ($operator) {
'and' => $aNeg and $bNeg,
'or' => $aNeg or $bNeg,
'xor' => $aNeg xor $bNeg,
};

if ($negative) {
$value = $this->twosComplement($value);
}

$result = $this->toDecimal($value);

return $negative ? $this->neg($result) : $result;
}

/**
@pure


*/
private function twosComplement(string $number): string
{
$xor = str_repeat("\xff", strlen($number));

$number ^= $xor;

for ($i = strlen($number) - 1; $i >= 0; $i--) {
$byte = ord($number[$i]);

if (++$byte !== 256) {
$number[$i] = chr($byte);

break;
}

$number[$i] = "\x00";

if ($i === 0) {
$number = "\x01" . $number;
}
}

return $number;
}

/**
@pure




*/
private function toBinary(string $number): string
{
$result = '';

while ($number !== '0') {
[$number, $remainder] = $this->divQR($number, '256');
$result .= chr((int) $remainder);
}

return strrev($result);
}

/**
@pure




*/
private function toDecimal(string $bytes): string
{
$result = '0';
$power = '1';

for ($i = strlen($bytes) - 1; $i >= 0; $i--) {
$index = ord($bytes[$i]);

if ($index !== 0) {
$result = $this->add(
$result,
($index === 1) ? $power : $this->mul($power, (string) $index),
);
}

if ($i !== 0) {
$power = $this->mul($power, '256');
}
}

return $result;
}
}
