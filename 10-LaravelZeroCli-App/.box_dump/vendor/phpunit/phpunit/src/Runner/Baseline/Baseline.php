<?php declare(strict_types=1);








namespace PHPUnit\Runner\Baseline;

use function ksort;
use function strcmp;
use function usort;

/**
@no-named-arguments


*/
final class Baseline
{
public const int VERSION = 1;




private array $issues = [];

public function add(Issue $issue): void
{
if (!isset($this->issues[$issue->file()])) {
$this->issues[$issue->file()] = [];
}

if (!isset($this->issues[$issue->file()][$issue->line()])) {
$this->issues[$issue->file()][$issue->line()] = [];
}

$this->issues[$issue->file()][$issue->line()][] = $issue;
}

public function has(Issue $issue): bool
{
if (!isset($this->issues[$issue->file()][$issue->line()])) {
return false;
}

foreach ($this->issues[$issue->file()][$issue->line()] as $_issue) {
if ($_issue->equals($issue)) {
return true;
}
}

return false;
}




public function groupedByFileAndLine(): array
{
$issues = $this->issues;

ksort($issues);

foreach ($issues as &$lines) {
ksort($lines);

foreach ($lines as &$issuesOnLine) {
usort(
$issuesOnLine,
static fn (Issue $a, Issue $b): int => strcmp($a->description(), $b->description()),
);
}
}

return $issues;
}
}
