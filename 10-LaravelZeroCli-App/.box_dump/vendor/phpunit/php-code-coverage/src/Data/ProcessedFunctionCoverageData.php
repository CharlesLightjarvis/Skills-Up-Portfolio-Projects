<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Data;

use NoDiscard;
use SebastianBergmann\CodeCoverage\Driver\XdebugDriver;

/**
@phpstan-import-type
@phpstan-import-type


*/
final readonly class ProcessedFunctionCoverageData
{

public array $branches;


public array $paths;




public static function fromXdebugCoverage(array $xdebugCoverageData): self
{
$branches = [];

foreach ($xdebugCoverageData['branches'] as $branchId => $branch) {
$branches[$branchId] = ProcessedBranchCoverageData::fromXdebugCoverage($branch);
}
$paths = [];

foreach ($xdebugCoverageData['paths'] as $pathId => $path) {
$paths[$pathId] = ProcessedPathCoverageData::fromXdebugCoverage($path);
}

return new self(
$branches,
$paths,
);
}





public function __construct(
array $branches,
array $paths,
) {
$this->paths = $paths;
$this->branches = $branches;
}

#[NoDiscard]
public function merge(self $data): self
{
$branches = null;

if ($data->branches !== $this->branches) {
$branches = $this->branches;

foreach ($data->branches as $branchId => $branch) {
if (!isset($branches[$branchId])) {
$branches[$branchId] = $branch;
} else {
$branches[$branchId] = $branches[$branchId]->merge($branch);
}
}
}

$paths = null;

if ($data->paths !== $this->paths) {
$paths = $this->paths;

foreach ($data->paths as $pathId => $path) {
if (!isset($paths[$pathId])) {
$paths[$pathId] = $path;
} else {
$paths[$pathId] = $paths[$pathId]->merge($path);
}
}
}

if ($branches === null && $paths === null) {
return $this;
}

return new self(
$branches ?? $this->branches,
$paths ?? $this->paths,
);
}




public function recordBranchHit(int $branchId, string $testCaseId): void
{
$this->branches[$branchId]->recordHit($testCaseId);
}




public function recordPathHit(int $pathId, string $testCaseId): void
{
$this->paths[$pathId]->recordHit($testCaseId);
}
}
