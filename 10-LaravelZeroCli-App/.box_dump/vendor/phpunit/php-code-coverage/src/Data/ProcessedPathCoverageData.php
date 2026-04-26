<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Data;

use function array_merge;
use function array_unique;
use NoDiscard;
use SebastianBergmann\CodeCoverage\Driver\XdebugDriver;

/**
@phpstan-import-type
@phpstan-import-type


*/
final class ProcessedPathCoverageData
{

public readonly array $path;


public array $hit;




public static function fromXdebugCoverage(array $xdebugCoverageData): self
{
return new self(
$xdebugCoverageData['path'],
[],
);
}





public function __construct(
array $path,
array $hit,
) {
$this->hit = $hit;
$this->path = $path;
}

#[NoDiscard]
public function merge(self $data): self
{
if ($data->hit === []) {
return $this;
}

return new self(
$this->path,
array_unique(array_merge($this->hit, $data->hit)),
);
}




public function recordHit(string $testCaseId): void
{
$this->hit[] = $testCaseId;
}
}
