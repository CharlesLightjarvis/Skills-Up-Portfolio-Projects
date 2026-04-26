<?php declare(strict_types=1);








namespace PHPUnit\Metadata\Version;

use function preg_match;
use PharIo\Version\UnsupportedVersionConstraintException;
use PharIo\Version\VersionConstraintParser;
use PHPUnit\Metadata\InvalidVersionRequirementException;
use PHPUnit\Util\InvalidVersionOperatorException;
use PHPUnit\Util\VersionComparisonOperator;

/**
@immutable
@no-named-arguments

*/
abstract readonly class Requirement
{
private const string VERSION_COMPARISON = "/(?P<operator>!=|<|<=|<>|=|==|>|>=)?\s*(?P<version>[\d\.-]+(dev|(RC|alpha|beta)[\d\.])?)[ \t]*\r?$/m";





public static function from(string $versionRequirement): self
{
try {
return new ConstraintRequirement(
(new VersionConstraintParser)->parse(
$versionRequirement,
),
);
} catch (UnsupportedVersionConstraintException) {
if (preg_match(self::VERSION_COMPARISON, $versionRequirement, $matches) > 0) {
return new ComparisonRequirement(
$matches['version'],
new VersionComparisonOperator(
$matches['operator'] !== '' ? $matches['operator'] : '>=',
),
);
}
}

throw new InvalidVersionRequirementException;
}

abstract public function isSatisfiedBy(string $version): bool;

abstract public function asString(): string;
}
