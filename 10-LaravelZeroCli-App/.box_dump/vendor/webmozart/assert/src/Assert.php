<?php

declare(strict_types=1);










namespace Webmozart\Assert;

use ArrayAccess;
use Closure;
use Countable;
use DateTime;
use DateTimeImmutable;
use ReflectionFunction;
use ReflectionProperty;
use Throwable;
use Traversable;








class Assert
{
use Mixin;

/**
@psalm-pure
@psalm-assert




*/
public static function string(mixed $value, string|callable $message = ''): string
{
if (!\is_string($value)) {
$message = static::resolveMessage($message);

static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a string. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert
@psalm-return





*/
public static function stringNotEmpty(mixed $value, string|callable $message = ''): string
{
static::string($value, $message);
static::notSame($value, '', $message);

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function integer(mixed $value, string|callable $message = ''): int
{
if (!\is_int($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an integer. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert




*/
public static function integerish(mixed $value, string|callable $message = ''): int|float|string
{
if (!\is_numeric($value) || $value != (int) $value) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an integerish value. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert
@psalm-return






*/
public static function positiveInteger(mixed $value, string|callable $message = ''): int
{
static::integer($value, $message);

if ($value < 1) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a positive integer. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert
@psalm-return




*/
public static function notNegativeInteger(mixed $value, string|callable $message = ''): int
{
static::integer($value, $message);

if ($value < 0) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a non negative integer. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert
@psalm-return




*/
public static function negativeInteger(mixed $value, string|callable $message = ''): int
{
static::integer($value, $message);

if ($value >= 0) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a negative integer. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function float(mixed $value, string|callable $message = ''): float
{
if (!\is_float($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a float. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function numeric(mixed $value, string|callable $message = ''): int|float|string
{
if (!\is_numeric($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a numeric. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert
@psalm-return






*/
public static function natural(mixed $value, string|callable $message = ''): int
{
if (!\is_int($value) || $value < 0) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a non-negative integer. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function boolean(mixed $value, string|callable $message = ''): bool
{
if (!\is_bool($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a boolean. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function scalar(mixed $value, string|callable $message = ''): int|bool|float|string
{
if (!\is_scalar($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a scalar. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function object(mixed $value, string|callable $message = ''): object
{
if (!\is_object($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an object. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert
@psalm-return






*/
public static function objectish(mixed $value, string|callable $message = ''): object|string
{
if (!\is_object($value) && !\is_string($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an objectish value. Got: %s',
static::typeToString($value)
));
}

if (\is_string($value) && !\class_exists($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected class to be defined. Got: %s',
$value
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert
@psalm-return








*/
public static function resource(mixed $value, ?string $type = null, string|callable $message = ''): mixed
{
if (!\is_resource($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a resource. Got: %s',
static::typeToString($value),
$type 
));
}

if ($type && $type !== \get_resource_type($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a resource of type %2$s. Got: %s',
static::typeToString($value),
$type
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function isInitialized(mixed $value, string $property, string|callable $message = ''): object
{
Assert::object($value);

$reflectionProperty = new ReflectionProperty($value, $property);

if (!$reflectionProperty->isInitialized($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected property %s to be initialized.',
$property,
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function isCallable(mixed $value, string|callable $message = ''): callable
{
if (!\is_callable($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a callable. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function isArray(mixed $value, string|callable $message = ''): array
{
if (!\is_array($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an array. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function isArrayAccessible(mixed $value, string|callable $message = ''): array|ArrayAccess
{
if (!\is_array($value) && !($value instanceof ArrayAccess)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an array accessible. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function isCountable(mixed $value, string|callable $message = ''): array|Countable
{
if (!\is_array($value) && !($value instanceof Countable)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a countable. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function isIterable(mixed $value, string|callable $message = ''): iterable
{
if (!\is_array($value) && !($value instanceof Traversable)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an iterable. Got: %s',
static::typeToString($value)
));
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert
@psalm-param








*/
public static function isInstanceOf(mixed $value, mixed $class, string|callable $message = ''): object
{
static::string($class, 'Expected class as a string. Got: %s');

if (!($value instanceof $class)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an instance of %2$s. Got: %s',
static::typeToString($value),
$class
));
}

return $value;
}

/**
@template
@psalm-assert
@psalm-param
@@return !T






*/
public static function notInstanceOf(mixed $value, mixed $class, string|callable $message = ''): object
{
static::string($class, 'Expected class as a string. Got: %s');

if (!\is_object($value) || $value instanceof $class) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an instance other than %2$s. Got: %s',
static::typeToString($value),
$class
));
}

return $value;
}

/**
@template
@psalm-assert








*/
public static function isInstanceOfAny(mixed $value, mixed $classes, string|callable $message = ''): object
{
static::isIterable($classes);

foreach ($classes as $class) {
static::string($class, 'Expected class as a string. Got: %s');

if ($value instanceof $class) {
return $value;
}
}

$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an instance of any of %2$s. Got: %s',
static::typeToString($value),
\implode(', ', \array_map(static::valueToString(...), \iterator_to_array($classes)))
));
}

/**
@template
@psalm-assert








*/
public static function isNotInstanceOfAny(mixed $value, mixed $classes, string|callable $message = ''): mixed
{
static::isIterable($classes);

foreach ($classes as $class) {
static::string($class, 'Expected class as a string. Got: %s');

if ($value instanceof $class) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected not an instance of %2$s. Got: %s',
static::typeToString($value),
\implode(', ', \array_map(static::valueToString(...), \iterator_to_array($classes)))
));
}
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert








*/
public static function isAOf(mixed $value, mixed $class, string|callable $message = ''): object|string
{
static::string($class, 'Expected class as a string. Got: %s');

if (!\is_a($value, $class, \is_string($value))) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an instance of this class or to this class among its parents "%2$s". Got: %s',
static::valueToString($value),
$class
));
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert









*/
public static function isNotA(mixed $value, mixed $class, string|callable $message = ''): object|string
{
static::objectish($value, $message);
static::string($class, 'Expected class as a string. Got: %s');

if (\is_a($value, $class, \is_string($value))) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an instance of this class or to this class among its parents other than "%2$s". Got: %s',
static::valueToString($value),
$class
));
}

return $value;
}

/**
@psalm-pure
@psalm-param






*/
public static function isAnyOf(mixed $value, mixed $classes, string|callable $message = ''): object|string
{
static::objectish($value, $message);
static::isIterable($classes);

foreach ($classes as $class) {
static::string($class, 'Expected class as a string. Got: %s');

if (\is_a($value, $class, \is_string($value))) {
return $value;
}
}

$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an instance of any of this classes or any of those classes among their parents "%2$s". Got: %s',
static::valueToString($value),
\implode(', ', \iterator_to_array($classes))
));
}

/**
@psalm-pure
@psalm-assert
@psalm-return






*/
public static function isEmpty(mixed $value, string|callable $message = ''): mixed
{
if (!empty($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an empty value. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert
@psalm-return






*/
public static function notEmpty(mixed $value, string|callable $message = ''): mixed
{
if (empty($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a non-empty value. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function null(mixed $value, string|callable $message = ''): null
{
if (null !== $value) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected null. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert
@psalm-return






*/
public static function notNull(mixed $value, string|callable $message = ''): mixed
{
if (null === $value) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(
$message ?: 'Expected a value other than null.'
);
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function true(mixed $value, string|callable $message = ''): true
{
if (true !== $value) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to be true. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function false(mixed $value, string|callable $message = ''): false
{
if (false !== $value) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to be false. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function notFalse(mixed $value, string|callable $message = ''): mixed
{
if (false === $value) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(
$message ?: 'Expected a value other than false.'
);
}

return $value;
}

/**
@psalm-pure
@psalm-param




*/
public static function ip(mixed $value, string|callable $message = ''): string
{
static::string($value, $message);

if (false === \filter_var($value, \FILTER_VALIDATE_IP)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to be an IP. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-param




*/
public static function ipv4(mixed $value, string|callable $message = ''): string
{
static::string($value, $message);

if (false === \filter_var($value, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to be an IPv4. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-param




*/
public static function ipv6(mixed $value, string|callable $message = ''): string
{
static::string($value, $message);

if (false === \filter_var($value, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to be an IPv6. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-param




*/
public static function email(mixed $value, string|callable $message = ''): string
{
static::string($value, $message);

if (false === \filter_var($value, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to be a valid e-mail address. Got: %s',
static::valueToString($value)
));
}

return $value;
}









public static function uniqueValues(mixed $values, string|callable $message = ''): array
{
static::isArray($values);

$allValues = \count($values);
$uniqueValues = \count(\array_unique($values));

if ($allValues !== $uniqueValues) {
$difference = $allValues - $uniqueValues;

$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an array of unique values, but %s of them %s duplicated',
$difference,
1 === $difference ? 'is' : 'are'
));
}

return $values;
}






public static function eq(mixed $value, mixed $expect, string|callable $message = ''): mixed
{
if ($expect != $value) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value equal to %2$s. Got: %s',
static::valueToString($value),
static::valueToString($expect)
));
}

return $value;
}






public static function notEq(mixed $value, mixed $expect, string|callable $message = ''): mixed
{
if ($expect == $value) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a different value than %s.',
static::valueToString($expect)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function same(mixed $value, mixed $expect, string|callable $message = ''): mixed
{
if ($expect !== $value) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value identical to %2$s. Got: %s',
static::valueToString($value),
static::valueToString($expect)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function notSame(mixed $value, mixed $expect, string|callable $message = ''): mixed
{
if ($expect === $value) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value not identical to %s.',
static::valueToString($expect)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function greaterThan(mixed $value, mixed $limit, string|callable $message = ''): mixed
{
if ($value <= $limit) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value greater than %2$s. Got: %s',
static::valueToString($value),
static::valueToString($limit)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function greaterThanEq(mixed $value, mixed $limit, string|callable $message = ''): mixed
{
if ($value < $limit) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value greater than or equal to %2$s. Got: %s',
static::valueToString($value),
static::valueToString($limit)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function lessThan(mixed $value, mixed $limit, string|callable $message = ''): mixed
{
if ($value >= $limit) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value less than %2$s. Got: %s',
static::valueToString($value),
static::valueToString($limit)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function lessThanEq(mixed $value, mixed $limit, string|callable $message = ''): mixed
{
if ($value > $limit) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value less than or equal to %2$s. Got: %s',
static::valueToString($value),
static::valueToString($limit)
));
}

return $value;
}

/**
@psalm-pure






*/
public static function range(mixed $value, mixed $min, mixed $max, string|callable $message = ''): mixed
{
if ($value < $min || $value > $max) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value between %2$s and %3$s. Got: %s',
static::valueToString($value),
static::valueToString($min),
static::valueToString($max)
));
}

return $value;
}

/**
@psalm-pure






*/
public static function oneOf(mixed $value, mixed $values, string|callable $message = ''): mixed
{
static::inArray($value, $values, $message);

return $value;
}

/**
@psalm-pure






*/
public static function inArray(mixed $value, mixed $values, string|callable $message = ''): mixed
{
static::isArray($values);

if (!\in_array($value, $values, true)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected one of: %2$s. Got: %s',
static::valueToString($value),
\implode(', ', \array_map(static::valueToString(...), $values))
));
}

return $value;
}

/**
@psalm-pure






*/
public static function notOneOf(mixed $value, mixed $values, string|callable $message = ''): mixed
{
static::notInArray($value, $values, $message);

return $value;
}

/**
@psalm-pure









*/
public static function notInArray(mixed $value, mixed $values, string|callable $message = ''): mixed
{
static::isArray($values);

if (\in_array($value, $values, true)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: '%2$s was not expected to contain a value. Got: %s',
static::valueToString($value),
\implode(', ', \array_map(static::valueToString(...), $values))
));
}

return $value;
}

/**
@psalm-pure




*/
public static function contains(mixed $value, mixed $subString, string|callable $message = ''): string
{
static::string($value);
static::string($subString);

if (!\str_contains($value, $subString)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to contain %2$s. Got: %s',
static::valueToString($value),
static::valueToString($subString)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function notContains(mixed $value, mixed $subString, string|callable $message = ''): string
{
static::string($value);
static::string($subString);

if (\str_contains($value, $subString)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: '%2$s was not expected to be contained in a value. Got: %s',
static::valueToString($value),
static::valueToString($subString)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function notWhitespaceOnly(mixed $value, string|callable $message = ''): string
{
static::string($value);

if (\preg_match('/^\s*$/', $value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a non-whitespace string. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function startsWith(mixed $value, mixed $prefix, string|callable $message = ''): string
{
static::string($value);
static::string($prefix);

if (!\str_starts_with($value, $prefix)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to start with %2$s. Got: %s',
static::valueToString($value),
static::valueToString($prefix)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function notStartsWith(mixed $value, mixed $prefix, string|callable $message = ''): string
{
static::string($value);
static::string($prefix);

if (\str_starts_with($value, $prefix)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value not to start with %2$s. Got: %s',
static::valueToString($value),
static::valueToString($prefix)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function startsWithLetter(mixed $value, string|callable $message = ''): string
{
static::string($value);

$valid = isset($value[0]);

if ($valid) {
$locale = \setlocale(LC_CTYPE, '0');
\setlocale(LC_CTYPE, 'C');
$valid = \ctype_alpha($value[0]);
\setlocale(LC_CTYPE, $locale);
}

if (!$valid) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to start with a letter. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function endsWith(mixed $value, mixed $suffix, string|callable $message = ''): string
{
static::string($value);
static::string($suffix);

if (!\str_ends_with($value, $suffix)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to end with %2$s. Got: %s',
static::valueToString($value),
static::valueToString($suffix)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function notEndsWith(mixed $value, mixed $suffix, string|callable $message = ''): string
{
static::string($value);
static::string($suffix);

if (\str_ends_with($value, $suffix)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value not to end with %2$s. Got: %s',
static::valueToString($value),
static::valueToString($suffix)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function regex(mixed $value, mixed $pattern, string|callable $message = ''): string
{
static::string($value);
static::string($pattern);

if (!\preg_match($pattern, $value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'The value %s does not match the expected pattern.',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function notRegex(mixed $value, mixed $pattern, string|callable $message = ''): string
{
static::string($value);
static::string($pattern);

if (\preg_match($pattern, $value, $matches, PREG_OFFSET_CAPTURE)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'The value %s matches the pattern %s (at offset %d).',
static::valueToString($value),
static::valueToString($pattern),
$matches[0][1]
));
}

return $value;
}

/**
@psalm-pure




*/
public static function unicodeLetters(mixed $value, string|callable $message = ''): string
{
static::string($value, $message);

if (!\preg_match('/^\p{L}+$/u', $value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to contain only Unicode letters. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function alpha(mixed $value, string|callable $message = ''): string
{
static::string($value, $message);

$locale = \setlocale(LC_CTYPE, '0');
\setlocale(LC_CTYPE, 'C');
$valid = !\ctype_alpha($value);
\setlocale(LC_CTYPE, $locale);

if ($valid) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to contain only letters. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function digits(mixed $value, string|callable $message = ''): string
{
static::string($value, $message);

$locale = \setlocale(LC_CTYPE, '0');
\setlocale(LC_CTYPE, 'C');
$valid = !\ctype_digit($value);
\setlocale(LC_CTYPE, $locale);

if ($valid) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to contain digits only. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function alnum(mixed $value, string|callable $message = ''): string
{
static::string($value, $message);

$locale = \setlocale(LC_CTYPE, '0');
\setlocale(LC_CTYPE, 'C');
$valid = !\ctype_alnum($value);
\setlocale(LC_CTYPE, $locale);

if ($valid) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to contain letters and digits only. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function lower(mixed $value, string|callable $message = ''): string
{
static::string($value, $message);

$locale = \setlocale(LC_CTYPE, '0');
\setlocale(LC_CTYPE, 'C');
$valid = !\ctype_lower($value);
\setlocale(LC_CTYPE, $locale);

if ($valid) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to contain lowercase characters only. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@psalm-assert





*/
public static function upper(mixed $value, string|callable $message = ''): string
{
static::string($value, $message);

$locale = \setlocale(LC_CTYPE, '0');
\setlocale(LC_CTYPE, 'C');
$valid = !\ctype_upper($value);
\setlocale(LC_CTYPE, $locale);

if ($valid) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to contain uppercase characters only. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure




*/
public static function length(mixed $value, mixed $length, string|callable $message = ''): string
{
static::string($value);
static::integerish($length);

if ($length !== static::strlen($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to contain %2$s characters. Got: %s',
static::valueToString($value),
$length
));
}

return $value;
}

/**
@psalm-pure






*/
public static function minLength(mixed $value, mixed $min, string|callable $message = ''): string
{
static::string($value);
static::integerish($min);

if (static::strlen($value) < $min) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to contain at least %2$s characters. Got: %s',
static::valueToString($value),
$min
));
}

return $value;
}

/**
@psalm-pure






*/
public static function maxLength(mixed $value, mixed $max, string|callable $message = ''): string
{
static::string($value);
static::integerish($max);

if (static::strlen($value) > $max) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to contain at most %2$s characters. Got: %s',
static::valueToString($value),
$max
));
}

return $value;
}

/**
@psalm-pure






*/
public static function lengthBetween(mixed $value, mixed $min, mixed $max, string|callable $message = ''): string
{
static::string($value);
static::integerish($min);
static::integerish($max);

$length = static::strlen($value);

if ($length < $min || $length > $max) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a value to contain between %2$s and %3$s characters. Got: %s',
static::valueToString($value),
$min,
$max
));
}

return $value;
}








public static function fileExists(mixed $value, string|callable $message = ''): string
{
static::string($value);

if (!\file_exists($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'The path %s does not exist.',
static::valueToString($value)
));
}

return $value;
}






public static function file(mixed $value, string|callable $message = ''): string
{
static::string($value);

if (!\is_file($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'The path %s is not a file.',
static::valueToString($value)
));
}

return $value;
}






public static function directory(mixed $value, string|callable $message = ''): string
{
static::string($value);

if (!\is_dir($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'The path %s is not a directory.',
static::valueToString($value)
));
}

return $value;
}






public static function readable(mixed $value, string|callable $message = ''): string
{
static::string($value);

if (!\is_readable($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'The path %s is not readable.',
static::valueToString($value)
));
}

return $value;
}






public static function writable(mixed $value, string|callable $message = ''): string
{
static::string($value);

if (!\is_writable($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'The path %s is not writable.',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-assert




*/
public static function classExists(mixed $value, string|callable $message = ''): string
{
static::string($value);

if (!\class_exists($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an existing class name. Got: %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert









*/
public static function subclassOf(mixed $value, mixed $class, string|callable $message = ''): string
{
static::string($value);
static::string($class);

if (!\is_subclass_of($value, $class)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected a sub-class of %2$s. Got: %s',
static::valueToString($value),
static::valueToString($class)
));
}

return $value;
}

/**
@psalm-assert




*/
public static function interfaceExists(mixed $value, string|callable $message = ''): string
{
static::string($value);

if (!\interface_exists($value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an existing interface name. got %s',
static::valueToString($value)
));
}

return $value;
}

/**
@psalm-pure
@template
@psalm-assert








*/
public static function implementsInterface(mixed $value, mixed $interface, string|callable $message = ''): object|string
{
static::objectish($value);

$implements = \class_implements($value);

static::isArray($implements);

if (!\in_array($interface, $implements, true)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an implementation of %2$s. Got: %s',
static::valueToString($value),
static::valueToString($interface)
));
}

return $value;
}

/**
@psalm-pure





*/
public static function propertyExists(mixed $classOrObject, mixed $property, string|callable $message = ''): object|string
{
static::objectish($classOrObject);

if (!\property_exists($classOrObject, $property)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected the property %s to exist.',
static::valueToString($property)
));
}

return $classOrObject;
}

/**
@psalm-pure
@psalm-param





*/
public static function propertyNotExists(mixed $classOrObject, mixed $property, string|callable $message = ''): mixed
{
if (!(\is_string($classOrObject) || \is_object($classOrObject)) || \property_exists($classOrObject, $property)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected the property %s to not exist.',
static::valueToString($property)
));
}

return $classOrObject;
}

/**
@psalm-pure
@psalm-param





*/
public static function methodExists(mixed $classOrObject, mixed $method, string|callable $message = ''): object|string
{
static::objectish($classOrObject);

if (!\method_exists($classOrObject, $method)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected the method %s to exist.',
static::valueToString($method)
));
}

return $classOrObject;
}

/**
@psalm-pure
@psalm-param





*/
public static function methodNotExists(mixed $classOrObject, mixed $method, string|callable $message = ''): mixed
{
static::objectish($classOrObject);

if (\method_exists($classOrObject, $method)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected the method %s to not exist.',
static::valueToString($method)
));
}

return $classOrObject;
}

/**
@psalm-pure





*/
public static function keyExists(mixed $array, string|int $key, string|callable $message = ''): array
{
static::isArray($array, $message);

if (!(isset($array[$key]) || \array_key_exists($key, $array))) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected the key %s to exist.',
static::valueToString($key)
));
}

return $array;
}

/**
@psalm-pure





*/
public static function keyNotExists(mixed $array, string|int $key, string|callable $message = ''): array
{
static::isArray($array, $message);

if (isset($array[$key]) || \array_key_exists($key, $array)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected the key %s to not exist.',
static::valueToString($key)
));
}

return $array;
}

/**
@psalm-pure
@psalm-assert







*/
public static function validArrayKey(mixed $value, string|callable $message = ''): string|int
{
if (!(\is_int($value) || \is_string($value))) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected string or integer. Got: %s',
static::typeToString($value)
));
}

return $value;
}






public static function count(mixed $array, mixed $number, string|callable $message = ''): array|Countable
{
static::isCountable($array);
static::integerish($number);

static::eq(
\count($array),
$number,
fn () => static::resolveMessage($message) ?: \sprintf(
$message ?: 'Expected an array to contain %d elements. Got: %d.',
$number,
\count($array)
)
);

return $array;
}






public static function minCount(mixed $array, mixed $min, string|callable $message = ''): array|Countable
{
static::isCountable($array);
static::integerish($min);

if (\count($array) < $min) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an array to contain at least %2$d elements. Got: %d',
\count($array),
$min
));
}

return $array;
}






public static function maxCount(mixed $array, mixed $max, string|callable $message = ''): array|Countable
{
static::isCountable($array);
static::integerish($max);

if (\count($array) > $max) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an array to contain at most %2$d elements. Got: %d',
\count($array),
$max
));
}

return $array;
}






public static function countBetween(mixed $array, mixed $min, mixed $max, string|callable $message = ''): array|Countable
{
static::isCountable($array);
static::integerish($min);
static::integerish($max);

$count = \count($array);

if ($count < $min || $count > $max) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Expected an array to contain between %2$d and %3$d elements. Got: %d',
$count,
$min,
$max
));
}

return $array;
}

/**
@psalm-pure
@psalm-assert
@psalm-return






*/
public static function isList(mixed $array, string|callable $message = ''): array
{
if (!\is_array($array) || !\array_is_list($array)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(
$message ?: 'Expected list - non-associative array.'
);
}

return $array;
}

/**
@psalm-pure
@psalm-assert
@psalm-return






*/
public static function isNonEmptyList(mixed $array, string|callable $message = ''): array
{
static::isList($array, $message);
static::notEmpty($array, $message);

return $array;
}

/**
@psalm-pure
@template
@psalm-assert









*/
public static function isMap(mixed $array, string|callable $message = ''): array
{
static::isArray($array, $message);

if (\count($array) > 0 && \array_is_list($array)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(
$message ?: 'Expected map - associative array with string keys.'
);
}

return $array;
}

/**
@psalm-assert







*/
public static function isStatic(mixed $callable, string|callable $message = ''): Closure|string
{
static::isCallable($callable, $message);

$callable = static::callableToClosure($callable);

$reflection = new ReflectionFunction($callable);

if (!$reflection->isStatic()) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(
$message ?: 'Closure is not static.'
);
}

return $callable;
}

/**
@psalm-assert







*/
public static function notStatic(mixed $callable, string|callable $message = ''): Closure|string
{
static::isCallable($callable, $message);

$callable = static::callableToClosure($callable);

$reflection = new ReflectionFunction($callable);

if ($reflection->isStatic()) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(
$message ?: 'Closure is not static.'
);
}

return $callable;
}

/**
@psalm-pure
@template
@psalm-assert
@psalm-assert









*/
public static function isNonEmptyMap(mixed $array, string|callable $message = ''): array
{
static::isMap($array, $message);
static::notEmpty($array, $message);

return $array;
}

/**
@psalm-pure




*/
public static function uuid(mixed $value, string|callable $message = ''): string
{
static::string($value, $message);

$originalValue = $value;
$value = \str_replace(['urn:', 'uuid:', '{', '}'], '', $value);



if ('00000000-0000-0000-0000-000000000000' === $value) {
return $originalValue;
}

if (!\preg_match('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/D', $value)) {
$message = self::resolveMessage($message);
static::reportInvalidArgument(\sprintf(
$message ?: 'Value %s is not a valid UUID.',
static::valueToString($value)
));
}

return $originalValue;
}

/**
@psalm-param



*/
public static function throws(mixed $expression, string $class = Throwable::class, string|callable $message = ''): callable
{
static::string($class);
static::isCallable($expression);

$actual = 'none';

try {
$expression();
} catch (Throwable $e) {
$actual = \get_class($e);
if ($e instanceof $class) {
return $expression;
}
}

$message = self::resolveMessage($message);

static::reportInvalidArgument($message ?: \sprintf(
'Expected to throw "%s", got "%s"',
$class,
$actual
));
}

/**
@psalm-pure


*/
protected static function callableToClosure(callable $callable): Closure|string
{
if (\is_string($callable) && \function_exists($callable)) {
return $callable;
}

if ($callable instanceof Closure) {
return $callable;
}

return $callable(...);
}

/**
@psalm-pure
*/
protected static function valueToString(mixed $value): string
{
if (null === $value) {
return 'null';
}

if (true === $value) {
return 'true';
}

if (false === $value) {
return 'false';
}

if (\is_array($value)) {
return 'array';
}

if (\is_object($value)) {
if (\method_exists($value, '__toString')) {
return \get_class($value).': '.self::valueToString($value->__toString());
}

if ($value instanceof DateTime || $value instanceof DateTimeImmutable) {
return \get_class($value).': '.self::valueToString($value->format('c'));
}

if (\enum_exists(\get_class($value))) {
return \get_class($value).'::'.$value->name;
}

return \get_class($value);
}

if (\is_resource($value)) {
return 'resource';
}

if (\is_string($value)) {
return '"'.$value.'"';
}

return (string) $value;
}

/**
@psalm-pure
*/
protected static function typeToString(mixed $value): string
{
return \is_object($value) ? \get_class($value) : \gettype($value);
}

protected static function strlen(string $value): int
{
if (!\function_exists('mb_detect_encoding')) {
return \strlen($value);
}

if (false === $encoding = \mb_detect_encoding($value)) {
return \strlen($value);
}

return \mb_strlen($value, $encoding);
}

/**
@psalm-pure


*/
protected static function reportInvalidArgument(string $message): never
{
throw new InvalidArgumentException($message);
}

/**
@psalm-pure


*/
protected static function resolveMessage(string|callable $message): string
{
return \is_callable($message) ? $message() : $message;
}

private function __construct()
{
}
}
