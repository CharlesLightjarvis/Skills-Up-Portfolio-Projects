<?php declare(strict_types=1);








namespace PHPUnit\Metadata\Api;

use function array_flip;
use function array_key_exists;
use function array_unique;
use function assert;
use function strtolower;
use function trim;
use PHPUnit\Framework\TestSize\TestSize;
use PHPUnit\Metadata\CoversClass;
use PHPUnit\Metadata\CoversFunction;
use PHPUnit\Metadata\Group;
use PHPUnit\Metadata\Parser\Registry;
use PHPUnit\Metadata\RequiresPhpExtension;
use PHPUnit\Metadata\UsesClass;
use PHPUnit\Metadata\UsesFunction;

/**
@no-named-arguments


*/
final class Groups
{



private static array $groupCache = [];







public function groups(string $className, string $methodName, bool $includeVirtual = true): array
{
$key = $className . '::' . $methodName . '::' . $includeVirtual;

if (array_key_exists($key, self::$groupCache)) {
return self::$groupCache[$key];
}

$groups = [];

foreach (Registry::parser()->forClassAndMethod($className, $methodName)->isGroup() as $group) {
assert($group instanceof Group);

$groups[] = $group->groupName();
}

if (!$includeVirtual) {
return self::$groupCache[$key] = array_unique($groups);
}

foreach (Registry::parser()->forClassAndMethod($className, $methodName) as $metadata) {
if ($metadata->isCoversClass()) {
assert($metadata instanceof CoversClass);

$groups[] = '__phpunit_covers_' . $this->canonicalizeName($metadata->className());

continue;
}

if ($metadata->isCoversFunction()) {
assert($metadata instanceof CoversFunction);

$groups[] = '__phpunit_covers_' . $this->canonicalizeName($metadata->functionName());

continue;
}

if ($metadata->isUsesClass()) {
assert($metadata instanceof UsesClass);

$groups[] = '__phpunit_uses_' . $this->canonicalizeName($metadata->className());

continue;
}

if ($metadata->isUsesFunction()) {
assert($metadata instanceof UsesFunction);

$groups[] = '__phpunit_uses_' . $this->canonicalizeName($metadata->functionName());

continue;
}

if ($metadata->isRequiresPhpExtension()) {
assert($metadata instanceof RequiresPhpExtension);

$groups[] = '__phpunit_requires_php_extension' . $this->canonicalizeName($metadata->extension());
}
}

return self::$groupCache[$key] = array_unique($groups);
}





public function size(string $className, string $methodName): TestSize
{
$groups = array_flip($this->groups($className, $methodName));

if (isset($groups['large'])) {
return TestSize::large();
}

if (isset($groups['medium'])) {
return TestSize::medium();
}

if (isset($groups['small'])) {
return TestSize::small();
}

return TestSize::unknown();
}

private function canonicalizeName(string $name): string
{
return strtolower(trim($name, '\\'));
}
}
