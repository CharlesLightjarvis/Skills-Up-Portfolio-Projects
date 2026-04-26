<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Driver;

use function sprintf;
use SebastianBergmann\CodeCoverage\BranchAndPathCoverageNotSupportedException;
use SebastianBergmann\CodeCoverage\Data\RawCodeCoverageData;




abstract class Driver
{



public const int LINE_NOT_EXECUTABLE = -2;




public const int LINE_NOT_EXECUTED = -1;




public const int LINE_EXECUTED = 1;




public const int BRANCH_NOT_HIT = 0;




public const int BRANCH_HIT = 1;
private bool $collectBranchAndPathCoverage = false;

public function canCollectBranchAndPathCoverage(): bool
{
return false;
}

public function collectsBranchAndPathCoverage(): bool
{
return $this->collectBranchAndPathCoverage;
}




public function enableBranchAndPathCoverage(): void
{
if (!$this->canCollectBranchAndPathCoverage()) {
throw new BranchAndPathCoverageNotSupportedException(
sprintf(
'%s does not support branch and path coverage',
$this->nameAndVersion(),
),
);
}

$this->collectBranchAndPathCoverage = true;
}

public function disableBranchAndPathCoverage(): void
{
$this->collectBranchAndPathCoverage = false;
}

public function isPcov(): bool
{
return false;
}

public function isXdebug(): bool
{
return false;
}

abstract public function nameAndVersion(): string;

abstract public function start(): void;

abstract public function stop(): RawCodeCoverageData;
}
