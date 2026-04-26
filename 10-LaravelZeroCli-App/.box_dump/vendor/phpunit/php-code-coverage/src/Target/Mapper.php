<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

use function array_keys;
use function array_merge;
use function array_unique;
use function strcasecmp;

/**
@phpstan-type
@phpstan-type
@phpstan-type
@immutable
@no-named-arguments




*/
final readonly class Mapper
{



private array $map;




public function __construct(array $map)
{
$this->map = $map;
}




public function mapTargets(TargetCollection $targets): array
{
$result = [];

foreach ($targets as $target) {
foreach ($this->mapTarget($target) as $file => $lines) {
if (!isset($result[$file])) {
$result[$file] = $lines;

continue;
}

$result[$file] = array_unique(array_merge($result[$file], $lines));
}
}

return $result;
}






public function mapTarget(Target $target): array
{
if (isset($this->map[$target->key()][$target->target()])) {
return $this->map[$target->key()][$target->target()];
}

foreach (array_keys($this->map[$target->key()]) as $key) {
if (strcasecmp($key, $target->target()) === 0) {
return $this->map[$target->key()][$key];
}
}

throw new InvalidCodeCoverageTargetException($target);
}







public function lookup(string $file, int $line): string
{
$key = $file . ':' . $line;

if (isset($this->map['reverseLookup'][$key])) {
return $this->map['reverseLookup'][$key];
}

return $key;
}
}
