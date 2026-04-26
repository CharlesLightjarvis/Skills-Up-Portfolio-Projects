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
final class ProcessedBranchCoverageData
{
public readonly int $op_start;
public readonly int $op_end;
public readonly int $line_start;
public readonly int $line_end;


public array $hit;


public readonly array $out;


public readonly array $out_hit;




public static function fromXdebugCoverage(array $xdebugCoverageData): self
{
return new self(
$xdebugCoverageData['op_start'],
$xdebugCoverageData['op_end'],
$xdebugCoverageData['line_start'],
$xdebugCoverageData['line_end'],
[],
$xdebugCoverageData['out'],
$xdebugCoverageData['out_hit'],
);
}






public function __construct(
int $op_start,
int $op_end,
int $line_start,
int $line_end,
array $hit,
array $out,
array $out_hit,
) {
$this->out_hit = $out_hit;
$this->out = $out;
$this->hit = $hit;
$this->line_end = $line_end;
$this->line_start = $line_start;
$this->op_end = $op_end;
$this->op_start = $op_start;
}

#[NoDiscard]
public function merge(self $data): self
{
if ($data->hit === []) {
return $this;
}

return new self(
$this->op_start,
$this->op_end,
$this->line_start,
$this->line_end,
array_unique(array_merge($this->hit, $data->hit)),
$this->out,
$this->out_hit,
);
}




public function recordHit(string $testCaseId): void
{
$this->hit[] = $testCaseId;
}
}
