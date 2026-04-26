<?php declare(strict_types=1);








namespace SebastianBergmann\Comparator;

use function assert;
use function spl_object_id;
use function sprintf;
use Closure;
use ReflectionFunction;

/**
@no-named-arguments


*/
final class ClosureComparator extends Comparator
{
public function accepts(mixed $expected, mixed $actual): bool
{
return $expected instanceof Closure && $actual instanceof Closure;
}

public function assertEquals(mixed $expected, mixed $actual, float $delta = 0.0, bool $canonicalize = false, bool $ignoreCase = false): void
{
assert($expected instanceof Closure);
assert($actual instanceof Closure);

/**
@phpstan-ignore */
if ($expected == $actual) {
return;
}

$expectedReflector = new ReflectionFunction($expected);
$actualReflector = new ReflectionFunction($actual);

$expectedFilename = $expectedReflector->getFileName();
$expectedStartLine = $expectedReflector->getStartLine();
$actualFilename = $actualReflector->getFileName();
$actualStartLine = $actualReflector->getStartLine();

assert($expectedFilename !== false);
assert($expectedStartLine !== false);
assert($actualFilename !== false);
assert($actualStartLine !== false);

throw new ComparisonFailure(
$expected,
$actual,
'Closure Object #' . spl_object_id($expected) . ' ()',
'Closure Object #' . spl_object_id($actual) . ' ()',
sprintf(
'Failed asserting that closure declared at %s:%d is equal to closure declared at %s:%d.',
$expectedFilename,
$expectedStartLine,
$actualFilename,
$actualStartLine,
),
);
}
}
