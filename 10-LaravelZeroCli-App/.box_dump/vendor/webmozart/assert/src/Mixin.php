<?php

declare(strict_types=1);

namespace Webmozart\Assert;

use ArrayAccess;
use Countable;
use Throwable;





trait Mixin
{
/**
@psalm-pure
@psalm-assert







*/
public static function nullOrString(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::string($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allString(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::string($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrString(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::string($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrStringNotEmpty(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::stringNotEmpty($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allStringNotEmpty(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::stringNotEmpty($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrStringNotEmpty(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::stringNotEmpty($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrInteger(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::integer($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allInteger(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::integer($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrInteger(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::integer($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrIntegerish(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::integerish($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allIntegerish(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::integerish($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrIntegerish(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::integerish($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrPositiveInteger(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::positiveInteger($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allPositiveInteger(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::positiveInteger($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrPositiveInteger(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::positiveInteger($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrNotNegativeInteger(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::notNegativeInteger($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNotNegativeInteger(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notNegativeInteger($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrNotNegativeInteger(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notNegativeInteger($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrNegativeInteger(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::negativeInteger($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNegativeInteger(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::negativeInteger($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrNegativeInteger(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::negativeInteger($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrFloat(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::float($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allFloat(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::float($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrFloat(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::float($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrNumeric(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::numeric($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNumeric(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::numeric($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrNumeric(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::numeric($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrNatural(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::natural($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNatural(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::natural($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrNatural(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::natural($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrBoolean(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::boolean($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allBoolean(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::boolean($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrBoolean(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::boolean($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrScalar(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::scalar($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allScalar(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::scalar($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrScalar(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::scalar($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrObject(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::object($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allObject(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::object($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrObject(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::object($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrObjectish(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::objectish($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allObjectish(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::objectish($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrObjectish(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::objectish($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert









*/
public static function nullOrResource(mixed $value, ?string $type = null, callable|string $message = ''): mixed
{
null === $value || static::resource($value, $type, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert









*/
public static function allResource(mixed $value, ?string $type = null, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::resource($entry, $type, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert









*/
public static function allNullOrResource(mixed $value, ?string $type = null, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::resource($entry, $type, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrIsCallable(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::isCallable($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allIsCallable(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::isCallable($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrIsCallable(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::isCallable($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrIsArray(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::isArray($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allIsArray(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::isArray($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrIsArray(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::isArray($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrIsArrayAccessible(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::isArrayAccessible($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allIsArrayAccessible(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::isArrayAccessible($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrIsArrayAccessible(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::isArrayAccessible($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrIsCountable(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::isCountable($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allIsCountable(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::isCountable($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrIsCountable(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::isCountable($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrIsIterable(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::isIterable($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allIsIterable(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::isIterable($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrIsIterable(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::isIterable($entry, $message);
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert
@psalm-param








*/
public static function nullOrIsInstanceOf(mixed $value, mixed $class, callable|string $message = ''): mixed
{
null === $value || static::isInstanceOf($value, $class, $message);

return $value;
}

/**
@psalm-pure
@template
@psalm-assert
@psalm-param








*/
public static function allIsInstanceOf(mixed $value, mixed $class, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::isInstanceOf($entry, $class, $message);
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert
@psalm-param








*/
public static function allNullOrIsInstanceOf(mixed $value, mixed $class, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::isInstanceOf($entry, $class, $message);
}

return $value;
}

/**
@template
@psalm-param







*/
public static function nullOrNotInstanceOf(mixed $value, mixed $class, callable|string $message = ''): mixed
{
null === $value || static::notInstanceOf($value, $class, $message);

return $value;
}

/**
@template
@psalm-param







*/
public static function allNotInstanceOf(mixed $value, mixed $class, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notInstanceOf($entry, $class, $message);
}

return $value;
}

/**
@template
@psalm-assert
@psalm-param







*/
public static function allNullOrNotInstanceOf(mixed $value, mixed $class, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notInstanceOf($entry, $class, $message);
}

return $value;
}

/**
@template
@psalm-assert







*/
public static function nullOrIsInstanceOfAny(mixed $value, mixed $classes, callable|string $message = ''): mixed
{
null === $value || static::isInstanceOfAny($value, $classes, $message);

return $value;
}

/**
@template
@psalm-assert







*/
public static function allIsInstanceOfAny(mixed $value, mixed $classes, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::isInstanceOfAny($entry, $classes, $message);
}

return $value;
}

/**
@template
@psalm-assert







*/
public static function allNullOrIsInstanceOfAny(mixed $value, mixed $classes, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::isInstanceOfAny($entry, $classes, $message);
}

return $value;
}

/**
@template
@psalm-assert







*/
public static function nullOrIsNotInstanceOfAny(mixed $value, mixed $classes, callable|string $message = ''): mixed
{
null === $value || static::isNotInstanceOfAny($value, $classes, $message);

return $value;
}

/**
@template
@psalm-assert







*/
public static function allIsNotInstanceOfAny(mixed $value, mixed $classes, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::isNotInstanceOfAny($entry, $classes, $message);
}

return $value;
}

/**
@template
@psalm-assert







*/
public static function allNullOrIsNotInstanceOfAny(mixed $value, mixed $classes, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::isNotInstanceOfAny($entry, $classes, $message);
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert







*/
public static function nullOrIsAOf(mixed $value, mixed $class, callable|string $message = ''): mixed
{
null === $value || static::isAOf($value, $class, $message);

return $value;
}

/**
@psalm-pure
@template
@psalm-assert







*/
public static function allIsAOf(mixed $value, mixed $class, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::isAOf($entry, $class, $message);
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert







*/
public static function allNullOrIsAOf(mixed $value, mixed $class, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::isAOf($entry, $class, $message);
}

return $value;
}

/**
@psalm-pure
@template








*/
public static function nullOrIsNotA(mixed $value, mixed $class, callable|string $message = ''): mixed
{
null === $value || static::isNotA($value, $class, $message);

return $value;
}

/**
@psalm-pure
@template








*/
public static function allIsNotA(mixed $value, mixed $class, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::isNotA($entry, $class, $message);
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert








*/
public static function allNullOrIsNotA(mixed $value, mixed $class, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::isNotA($entry, $class, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-param









*/
public static function nullOrIsAnyOf(mixed $value, mixed $classes, callable|string $message = ''): mixed
{
null === $value || static::isAnyOf($value, $classes, $message);

return $value;
}

/**
@psalm-pure
@psalm-param









*/
public static function allIsAnyOf(mixed $value, mixed $classes, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::isAnyOf($entry, $classes, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-param









*/
public static function allNullOrIsAnyOf(mixed $value, mixed $classes, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::isAnyOf($entry, $classes, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrIsEmpty(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::isEmpty($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allIsEmpty(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::isEmpty($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrIsEmpty(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::isEmpty($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrNotEmpty(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::notEmpty($value, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allNotEmpty(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notEmpty($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert
@@return iterable<!empty|null>






*/
public static function allNullOrNotEmpty(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notEmpty($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNull(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::null($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNotNull(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notNull($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrTrue(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::true($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allTrue(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::true($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrTrue(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::true($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrFalse(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::false($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allFalse(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::false($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrFalse(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::false($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrNotFalse(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::notFalse($value, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allNotFalse(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notFalse($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert
@@return iterable<!false|null>






*/
public static function allNullOrNotFalse(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notFalse($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-param







*/
public static function nullOrIp(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::ip($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-param







*/
public static function allIp(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::ip($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-param







*/
public static function allNullOrIp(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::ip($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-param







*/
public static function nullOrIpv4(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::ipv4($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-param







*/
public static function allIpv4(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::ipv4($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-param







*/
public static function allNullOrIpv4(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::ipv4($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-param







*/
public static function nullOrIpv6(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::ipv6($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-param







*/
public static function allIpv6(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::ipv6($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-param







*/
public static function allNullOrIpv6(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::ipv6($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-param







*/
public static function nullOrEmail(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::email($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-param







*/
public static function allEmail(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::email($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-param







*/
public static function allNullOrEmail(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::email($entry, $message);
}

return $value;
}








public static function nullOrUniqueValues(mixed $values, callable|string $message = ''): mixed
{
null === $values || static::uniqueValues($values, $message);

return $values;
}








public static function allUniqueValues(mixed $values, callable|string $message = ''): mixed
{
static::isIterable($values);

foreach ($values as $entry) {
static::uniqueValues($entry, $message);
}

return $values;
}








public static function allNullOrUniqueValues(mixed $values, callable|string $message = ''): mixed
{
static::isIterable($values);

foreach ($values as $entry) {
null === $entry || static::uniqueValues($entry, $message);
}

return $values;
}








public static function nullOrEq(mixed $value, mixed $expect, callable|string $message = ''): mixed
{
null === $value || static::eq($value, $expect, $message);

return $value;
}








public static function allEq(mixed $value, mixed $expect, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::eq($entry, $expect, $message);
}

return $value;
}








public static function allNullOrEq(mixed $value, mixed $expect, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::eq($entry, $expect, $message);
}

return $value;
}








public static function nullOrNotEq(mixed $value, mixed $expect, callable|string $message = ''): mixed
{
null === $value || static::notEq($value, $expect, $message);

return $value;
}








public static function allNotEq(mixed $value, mixed $expect, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notEq($entry, $expect, $message);
}

return $value;
}








public static function allNullOrNotEq(mixed $value, mixed $expect, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notEq($entry, $expect, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrSame(mixed $value, mixed $expect, callable|string $message = ''): mixed
{
null === $value || static::same($value, $expect, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allSame(mixed $value, mixed $expect, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::same($entry, $expect, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrSame(mixed $value, mixed $expect, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::same($entry, $expect, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrNotSame(mixed $value, mixed $expect, callable|string $message = ''): mixed
{
null === $value || static::notSame($value, $expect, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allNotSame(mixed $value, mixed $expect, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notSame($entry, $expect, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrNotSame(mixed $value, mixed $expect, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notSame($entry, $expect, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrGreaterThan(mixed $value, mixed $limit, callable|string $message = ''): mixed
{
null === $value || static::greaterThan($value, $limit, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allGreaterThan(mixed $value, mixed $limit, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::greaterThan($entry, $limit, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrGreaterThan(mixed $value, mixed $limit, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::greaterThan($entry, $limit, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrGreaterThanEq(mixed $value, mixed $limit, callable|string $message = ''): mixed
{
null === $value || static::greaterThanEq($value, $limit, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allGreaterThanEq(mixed $value, mixed $limit, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::greaterThanEq($entry, $limit, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrGreaterThanEq(mixed $value, mixed $limit, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::greaterThanEq($entry, $limit, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrLessThan(mixed $value, mixed $limit, callable|string $message = ''): mixed
{
null === $value || static::lessThan($value, $limit, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allLessThan(mixed $value, mixed $limit, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::lessThan($entry, $limit, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrLessThan(mixed $value, mixed $limit, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::lessThan($entry, $limit, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrLessThanEq(mixed $value, mixed $limit, callable|string $message = ''): mixed
{
null === $value || static::lessThanEq($value, $limit, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allLessThanEq(mixed $value, mixed $limit, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::lessThanEq($entry, $limit, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrLessThanEq(mixed $value, mixed $limit, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::lessThanEq($entry, $limit, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrRange(mixed $value, mixed $min, mixed $max, callable|string $message = ''): mixed
{
null === $value || static::range($value, $min, $max, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allRange(mixed $value, mixed $min, mixed $max, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::range($entry, $min, $max, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrRange(mixed $value, mixed $min, mixed $max, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::range($entry, $min, $max, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrOneOf(mixed $value, mixed $values, callable|string $message = ''): mixed
{
null === $value || static::oneOf($value, $values, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allOneOf(mixed $value, mixed $values, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::oneOf($entry, $values, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrOneOf(mixed $value, mixed $values, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::oneOf($entry, $values, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrInArray(mixed $value, mixed $values, callable|string $message = ''): mixed
{
null === $value || static::inArray($value, $values, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allInArray(mixed $value, mixed $values, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::inArray($entry, $values, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrInArray(mixed $value, mixed $values, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::inArray($entry, $values, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrNotOneOf(mixed $value, mixed $values, callable|string $message = ''): mixed
{
null === $value || static::notOneOf($value, $values, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allNotOneOf(mixed $value, mixed $values, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notOneOf($entry, $values, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrNotOneOf(mixed $value, mixed $values, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notOneOf($entry, $values, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrNotInArray(mixed $value, mixed $values, callable|string $message = ''): mixed
{
null === $value || static::notInArray($value, $values, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allNotInArray(mixed $value, mixed $values, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notInArray($entry, $values, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrNotInArray(mixed $value, mixed $values, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notInArray($entry, $values, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrContains(mixed $value, mixed $subString, callable|string $message = ''): mixed
{
null === $value || static::contains($value, $subString, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allContains(mixed $value, mixed $subString, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::contains($entry, $subString, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrContains(mixed $value, mixed $subString, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::contains($entry, $subString, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrNotContains(mixed $value, mixed $subString, callable|string $message = ''): mixed
{
null === $value || static::notContains($value, $subString, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allNotContains(mixed $value, mixed $subString, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notContains($entry, $subString, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrNotContains(mixed $value, mixed $subString, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notContains($entry, $subString, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrNotWhitespaceOnly(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::notWhitespaceOnly($value, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allNotWhitespaceOnly(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notWhitespaceOnly($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrNotWhitespaceOnly(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notWhitespaceOnly($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrStartsWith(mixed $value, mixed $prefix, callable|string $message = ''): mixed
{
null === $value || static::startsWith($value, $prefix, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allStartsWith(mixed $value, mixed $prefix, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::startsWith($entry, $prefix, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrStartsWith(mixed $value, mixed $prefix, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::startsWith($entry, $prefix, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrNotStartsWith(mixed $value, mixed $prefix, callable|string $message = ''): mixed
{
null === $value || static::notStartsWith($value, $prefix, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allNotStartsWith(mixed $value, mixed $prefix, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notStartsWith($entry, $prefix, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrNotStartsWith(mixed $value, mixed $prefix, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notStartsWith($entry, $prefix, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrStartsWithLetter(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::startsWithLetter($value, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allStartsWithLetter(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::startsWithLetter($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrStartsWithLetter(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::startsWithLetter($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrEndsWith(mixed $value, mixed $suffix, callable|string $message = ''): mixed
{
null === $value || static::endsWith($value, $suffix, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allEndsWith(mixed $value, mixed $suffix, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::endsWith($entry, $suffix, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrEndsWith(mixed $value, mixed $suffix, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::endsWith($entry, $suffix, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrNotEndsWith(mixed $value, mixed $suffix, callable|string $message = ''): mixed
{
null === $value || static::notEndsWith($value, $suffix, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allNotEndsWith(mixed $value, mixed $suffix, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notEndsWith($entry, $suffix, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrNotEndsWith(mixed $value, mixed $suffix, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notEndsWith($entry, $suffix, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrRegex(mixed $value, mixed $pattern, callable|string $message = ''): mixed
{
null === $value || static::regex($value, $pattern, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allRegex(mixed $value, mixed $pattern, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::regex($entry, $pattern, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrRegex(mixed $value, mixed $pattern, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::regex($entry, $pattern, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrNotRegex(mixed $value, mixed $pattern, callable|string $message = ''): mixed
{
null === $value || static::notRegex($value, $pattern, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allNotRegex(mixed $value, mixed $pattern, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::notRegex($entry, $pattern, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrNotRegex(mixed $value, mixed $pattern, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::notRegex($entry, $pattern, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrUnicodeLetters(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::unicodeLetters($value, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allUnicodeLetters(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::unicodeLetters($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrUnicodeLetters(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::unicodeLetters($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrAlpha(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::alpha($value, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allAlpha(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::alpha($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrAlpha(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::alpha($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrDigits(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::digits($value, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allDigits(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::digits($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrDigits(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::digits($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrAlnum(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::alnum($value, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allAlnum(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::alnum($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrAlnum(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::alnum($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrLower(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::lower($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allLower(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::lower($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrLower(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::lower($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrUpper(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::upper($value, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allUpper(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::upper($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert
@@return iterable<!lowercase-string|null>






*/
public static function allNullOrUpper(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::upper($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrLength(mixed $value, mixed $length, callable|string $message = ''): mixed
{
null === $value || static::length($value, $length, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allLength(mixed $value, mixed $length, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::length($entry, $length, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrLength(mixed $value, mixed $length, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::length($entry, $length, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrMinLength(mixed $value, mixed $min, callable|string $message = ''): mixed
{
null === $value || static::minLength($value, $min, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allMinLength(mixed $value, mixed $min, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::minLength($entry, $min, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrMinLength(mixed $value, mixed $min, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::minLength($entry, $min, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrMaxLength(mixed $value, mixed $max, callable|string $message = ''): mixed
{
null === $value || static::maxLength($value, $max, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allMaxLength(mixed $value, mixed $max, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::maxLength($entry, $max, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrMaxLength(mixed $value, mixed $max, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::maxLength($entry, $max, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function nullOrLengthBetween(mixed $value, mixed $min, mixed $max, callable|string $message = ''): mixed
{
null === $value || static::lengthBetween($value, $min, $max, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allLengthBetween(mixed $value, mixed $min, mixed $max, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::lengthBetween($entry, $min, $max, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrLengthBetween(mixed $value, mixed $min, mixed $max, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::lengthBetween($entry, $min, $max, $message);
}

return $value;
}








public static function nullOrFileExists(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::fileExists($value, $message);

return $value;
}








public static function allFileExists(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::fileExists($entry, $message);
}

return $value;
}








public static function allNullOrFileExists(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::fileExists($entry, $message);
}

return $value;
}








public static function nullOrFile(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::file($value, $message);

return $value;
}








public static function allFile(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::file($entry, $message);
}

return $value;
}








public static function allNullOrFile(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::file($entry, $message);
}

return $value;
}








public static function nullOrDirectory(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::directory($value, $message);

return $value;
}








public static function allDirectory(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::directory($entry, $message);
}

return $value;
}








public static function allNullOrDirectory(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::directory($entry, $message);
}

return $value;
}








public static function nullOrReadable(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::readable($value, $message);

return $value;
}








public static function allReadable(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::readable($entry, $message);
}

return $value;
}








public static function allNullOrReadable(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::readable($entry, $message);
}

return $value;
}








public static function nullOrWritable(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::writable($value, $message);

return $value;
}








public static function allWritable(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::writable($entry, $message);
}

return $value;
}








public static function allNullOrWritable(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::writable($entry, $message);
}

return $value;
}

/**
@psalm-assert






*/
public static function nullOrClassExists(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::classExists($value, $message);

return $value;
}

/**
@psalm-assert






*/
public static function allClassExists(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::classExists($entry, $message);
}

return $value;
}

/**
@psalm-assert






*/
public static function allNullOrClassExists(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::classExists($entry, $message);
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert








*/
public static function nullOrSubclassOf(mixed $value, mixed $class, callable|string $message = ''): mixed
{
null === $value || static::subclassOf($value, $class, $message);

return $value;
}

/**
@psalm-pure
@template
@psalm-assert








*/
public static function allSubclassOf(mixed $value, mixed $class, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::subclassOf($entry, $class, $message);
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert








*/
public static function allNullOrSubclassOf(mixed $value, mixed $class, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::subclassOf($entry, $class, $message);
}

return $value;
}

/**
@psalm-assert






*/
public static function nullOrInterfaceExists(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::interfaceExists($value, $message);

return $value;
}

/**
@psalm-assert






*/
public static function allInterfaceExists(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::interfaceExists($entry, $message);
}

return $value;
}

/**
@psalm-assert






*/
public static function allNullOrInterfaceExists(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::interfaceExists($entry, $message);
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert









*/
public static function nullOrImplementsInterface(mixed $value, mixed $interface, callable|string $message = ''): mixed
{
null === $value || static::implementsInterface($value, $interface, $message);

return $value;
}

/**
@psalm-pure
@template
@psalm-assert









*/
public static function allImplementsInterface(mixed $value, mixed $interface, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::implementsInterface($entry, $interface, $message);
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert









*/
public static function allNullOrImplementsInterface(mixed $value, mixed $interface, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::implementsInterface($entry, $interface, $message);
}

return $value;
}

/**
@psalm-pure







*/
public static function nullOrPropertyExists(mixed $classOrObject, mixed $property, callable|string $message = ''): mixed
{
null === $classOrObject || static::propertyExists($classOrObject, $property, $message);

return $classOrObject;
}

/**
@psalm-pure







*/
public static function allPropertyExists(mixed $classOrObject, mixed $property, callable|string $message = ''): mixed
{
static::isIterable($classOrObject);

foreach ($classOrObject as $entry) {
static::propertyExists($entry, $property, $message);
}

return $classOrObject;
}

/**
@psalm-pure







*/
public static function allNullOrPropertyExists(mixed $classOrObject, mixed $property, callable|string $message = ''): mixed
{
static::isIterable($classOrObject);

foreach ($classOrObject as $entry) {
null === $entry || static::propertyExists($entry, $property, $message);
}

return $classOrObject;
}

/**
@psalm-pure
@psalm-param








*/
public static function nullOrPropertyNotExists(mixed $classOrObject, mixed $property, callable|string $message = ''): mixed
{
null === $classOrObject || static::propertyNotExists($classOrObject, $property, $message);

return $classOrObject;
}

/**
@psalm-pure
@psalm-param








*/
public static function allPropertyNotExists(mixed $classOrObject, mixed $property, callable|string $message = ''): mixed
{
static::isIterable($classOrObject);

foreach ($classOrObject as $entry) {
static::propertyNotExists($entry, $property, $message);
}

return $classOrObject;
}

/**
@psalm-pure
@psalm-param








*/
public static function allNullOrPropertyNotExists(mixed $classOrObject, mixed $property, callable|string $message = ''): mixed
{
static::isIterable($classOrObject);

foreach ($classOrObject as $entry) {
null === $entry || static::propertyNotExists($entry, $property, $message);
}

return $classOrObject;
}

/**
@psalm-pure
@psalm-param








*/
public static function nullOrMethodExists(mixed $classOrObject, mixed $method, callable|string $message = ''): mixed
{
null === $classOrObject || static::methodExists($classOrObject, $method, $message);

return $classOrObject;
}

/**
@psalm-pure
@psalm-param








*/
public static function allMethodExists(mixed $classOrObject, mixed $method, callable|string $message = ''): mixed
{
static::isIterable($classOrObject);

foreach ($classOrObject as $entry) {
static::methodExists($entry, $method, $message);
}

return $classOrObject;
}

/**
@psalm-pure
@psalm-param








*/
public static function allNullOrMethodExists(mixed $classOrObject, mixed $method, callable|string $message = ''): mixed
{
static::isIterable($classOrObject);

foreach ($classOrObject as $entry) {
null === $entry || static::methodExists($entry, $method, $message);
}

return $classOrObject;
}

/**
@psalm-pure
@psalm-param








*/
public static function nullOrMethodNotExists(mixed $classOrObject, mixed $method, callable|string $message = ''): mixed
{
null === $classOrObject || static::methodNotExists($classOrObject, $method, $message);

return $classOrObject;
}

/**
@psalm-pure
@psalm-param








*/
public static function allMethodNotExists(mixed $classOrObject, mixed $method, callable|string $message = ''): mixed
{
static::isIterable($classOrObject);

foreach ($classOrObject as $entry) {
static::methodNotExists($entry, $method, $message);
}

return $classOrObject;
}

/**
@psalm-pure
@psalm-param








*/
public static function allNullOrMethodNotExists(mixed $classOrObject, mixed $method, callable|string $message = ''): mixed
{
static::isIterable($classOrObject);

foreach ($classOrObject as $entry) {
null === $entry || static::methodNotExists($entry, $method, $message);
}

return $classOrObject;
}

/**
@psalm-pure







*/
public static function nullOrKeyExists(mixed $array, string|int $key, callable|string $message = ''): mixed
{
null === $array || static::keyExists($array, $key, $message);

return $array;
}

/**
@psalm-pure







*/
public static function allKeyExists(mixed $array, string|int $key, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
static::keyExists($entry, $key, $message);
}

return $array;
}

/**
@psalm-pure







*/
public static function allNullOrKeyExists(mixed $array, string|int $key, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
null === $entry || static::keyExists($entry, $key, $message);
}

return $array;
}

/**
@psalm-pure







*/
public static function nullOrKeyNotExists(mixed $array, string|int $key, callable|string $message = ''): mixed
{
null === $array || static::keyNotExists($array, $key, $message);

return $array;
}

/**
@psalm-pure







*/
public static function allKeyNotExists(mixed $array, string|int $key, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
static::keyNotExists($entry, $key, $message);
}

return $array;
}

/**
@psalm-pure







*/
public static function allNullOrKeyNotExists(mixed $array, string|int $key, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
null === $entry || static::keyNotExists($entry, $key, $message);
}

return $array;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrValidArrayKey(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::validArrayKey($value, $message);

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allValidArrayKey(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::validArrayKey($entry, $message);
}

return $value;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrValidArrayKey(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::validArrayKey($entry, $message);
}

return $value;
}








public static function nullOrCount(mixed $array, mixed $number, callable|string $message = ''): mixed
{
null === $array || static::count($array, $number, $message);

return $array;
}








public static function allCount(mixed $array, mixed $number, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
static::count($entry, $number, $message);
}

return $array;
}








public static function allNullOrCount(mixed $array, mixed $number, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
null === $entry || static::count($entry, $number, $message);
}

return $array;
}








public static function nullOrMinCount(mixed $array, mixed $min, callable|string $message = ''): mixed
{
null === $array || static::minCount($array, $min, $message);

return $array;
}








public static function allMinCount(mixed $array, mixed $min, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
static::minCount($entry, $min, $message);
}

return $array;
}








public static function allNullOrMinCount(mixed $array, mixed $min, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
null === $entry || static::minCount($entry, $min, $message);
}

return $array;
}








public static function nullOrMaxCount(mixed $array, mixed $max, callable|string $message = ''): mixed
{
null === $array || static::maxCount($array, $max, $message);

return $array;
}








public static function allMaxCount(mixed $array, mixed $max, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
static::maxCount($entry, $max, $message);
}

return $array;
}








public static function allNullOrMaxCount(mixed $array, mixed $max, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
null === $entry || static::maxCount($entry, $max, $message);
}

return $array;
}








public static function nullOrCountBetween(mixed $array, mixed $min, mixed $max, callable|string $message = ''): mixed
{
null === $array || static::countBetween($array, $min, $max, $message);

return $array;
}








public static function allCountBetween(mixed $array, mixed $min, mixed $max, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
static::countBetween($entry, $min, $max, $message);
}

return $array;
}








public static function allNullOrCountBetween(mixed $array, mixed $min, mixed $max, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
null === $entry || static::countBetween($entry, $min, $max, $message);
}

return $array;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrIsList(mixed $array, callable|string $message = ''): mixed
{
null === $array || static::isList($array, $message);

return $array;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allIsList(mixed $array, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
static::isList($entry, $message);
}

return $array;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrIsList(mixed $array, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
null === $entry || static::isList($entry, $message);
}

return $array;
}

/**
@psalm-pure
@psalm-assert







*/
public static function nullOrIsNonEmptyList(mixed $array, callable|string $message = ''): mixed
{
null === $array || static::isNonEmptyList($array, $message);

return $array;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allIsNonEmptyList(mixed $array, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
static::isNonEmptyList($entry, $message);
}

return $array;
}

/**
@psalm-pure
@psalm-assert







*/
public static function allNullOrIsNonEmptyList(mixed $array, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
null === $entry || static::isNonEmptyList($entry, $message);
}

return $array;
}

/**
@psalm-pure
@template
@psalm-assert








*/
public static function nullOrIsMap(mixed $array, callable|string $message = ''): mixed
{
null === $array || static::isMap($array, $message);

return $array;
}

/**
@psalm-pure
@template
@psalm-assert








*/
public static function allIsMap(mixed $array, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
static::isMap($entry, $message);
}

return $array;
}

/**
@psalm-pure
@template
@psalm-assert








*/
public static function allNullOrIsMap(mixed $array, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
null === $entry || static::isMap($entry, $message);
}

return $array;
}

/**
@psalm-assert







*/
public static function nullOrIsStatic(mixed $callable, callable|string $message = ''): mixed
{
null === $callable || static::isStatic($callable, $message);

return $callable;
}

/**
@psalm-assert







*/
public static function allIsStatic(mixed $callable, callable|string $message = ''): mixed
{
static::isIterable($callable);

foreach ($callable as $entry) {
static::isStatic($entry, $message);
}

return $callable;
}

/**
@psalm-assert







*/
public static function allNullOrIsStatic(mixed $callable, callable|string $message = ''): mixed
{
static::isIterable($callable);

foreach ($callable as $entry) {
null === $entry || static::isStatic($entry, $message);
}

return $callable;
}

/**
@psalm-assert







*/
public static function nullOrNotStatic(mixed $callable, callable|string $message = ''): mixed
{
null === $callable || static::notStatic($callable, $message);

return $callable;
}

/**
@psalm-assert







*/
public static function allNotStatic(mixed $callable, callable|string $message = ''): mixed
{
static::isIterable($callable);

foreach ($callable as $entry) {
static::notStatic($entry, $message);
}

return $callable;
}

/**
@psalm-assert







*/
public static function allNullOrNotStatic(mixed $callable, callable|string $message = ''): mixed
{
static::isIterable($callable);

foreach ($callable as $entry) {
null === $entry || static::notStatic($entry, $message);
}

return $callable;
}

/**
@psalm-pure
@template








*/
public static function nullOrIsNonEmptyMap(mixed $array, callable|string $message = ''): mixed
{
null === $array || static::isNonEmptyMap($array, $message);

return $array;
}

/**
@psalm-pure
@template








*/
public static function allIsNonEmptyMap(mixed $array, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
static::isNonEmptyMap($entry, $message);
}

return $array;
}

/**
@psalm-pure
@template
@psalm-assert
@psalm-assert
@@return iterable<!empty|null>







*/
public static function allNullOrIsNonEmptyMap(mixed $array, callable|string $message = ''): mixed
{
static::isIterable($array);

foreach ($array as $entry) {
null === $entry || static::isNonEmptyMap($entry, $message);
}

return $array;
}

/**
@psalm-pure






*/
public static function nullOrUuid(mixed $value, callable|string $message = ''): mixed
{
null === $value || static::uuid($value, $message);

return $value;
}

/**
@psalm-pure






*/
public static function allUuid(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
static::uuid($entry, $message);
}

return $value;
}

/**
@psalm-pure






*/
public static function allNullOrUuid(mixed $value, callable|string $message = ''): iterable
{
static::isIterable($value);

foreach ($value as $entry) {
null === $entry || static::uuid($entry, $message);
}

return $value;
}

/**
@psalm-param






*/
public static function nullOrThrows(mixed $expression, string $class = 'Throwable', callable|string $message = ''): mixed
{
null === $expression || static::throws($expression, $class, $message);

return $expression;
}

/**
@psalm-param






*/
public static function allThrows(mixed $expression, string $class = 'Throwable', callable|string $message = ''): mixed
{
static::isIterable($expression);

foreach ($expression as $entry) {
static::throws($entry, $class, $message);
}

return $expression;
}

/**
@psalm-param






*/
public static function allNullOrThrows(mixed $expression, string $class = 'Throwable', callable|string $message = ''): mixed
{
static::isIterable($expression);

foreach ($expression as $entry) {
null === $entry || static::throws($entry, $class, $message);
}

return $expression;
}
}
