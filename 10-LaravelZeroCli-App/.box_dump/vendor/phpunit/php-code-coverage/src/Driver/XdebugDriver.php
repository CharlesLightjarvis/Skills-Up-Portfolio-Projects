<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Driver;

use const XDEBUG_CC_BRANCH_CHECK;
use const XDEBUG_CC_DEAD_CODE;
use const XDEBUG_CC_UNUSED;
use const XDEBUG_FILTER_CODE_COVERAGE;
use const XDEBUG_PATH_INCLUDE;
use function extension_loaded;
use function in_array;
use function phpversion;
use function version_compare;
use function xdebug_get_code_coverage;
use function xdebug_info;
use function xdebug_set_filter;
use function xdebug_start_code_coverage;
use function xdebug_stop_code_coverage;
use SebastianBergmann\CodeCoverage\Data\RawCodeCoverageData;
use SebastianBergmann\CodeCoverage\Filter;

/**
@phpstan-type
@phpstan-type
@phpstan-type
@phpstan-type
@phpstan-type
@phpstan-type
@phpstan-type
@phpstan-type





















*/
final class XdebugDriver extends Driver
{





public function __construct(Filter $filter)
{
$this->ensureXdebugIsAvailable();

if (!$filter->isEmpty()) {
xdebug_set_filter(
XDEBUG_FILTER_CODE_COVERAGE,
XDEBUG_PATH_INCLUDE,
$filter->files(),
);
}
}

public function canCollectBranchAndPathCoverage(): bool
{
return true;
}

public function start(): void
{
$flags = XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE;

if ($this->collectsBranchAndPathCoverage()) {
$flags |= XDEBUG_CC_BRANCH_CHECK;
}

xdebug_start_code_coverage($flags);
}

public function stop(): RawCodeCoverageData
{
$data = xdebug_get_code_coverage();

xdebug_stop_code_coverage();

if ($this->collectsBranchAndPathCoverage()) {

return RawCodeCoverageData::fromXdebugWithPathCoverage($data);
}


return RawCodeCoverageData::fromXdebugWithoutPathCoverage($data);
}

public function nameAndVersion(): string
{
return 'Xdebug ' . phpversion('xdebug');
}

public function isXdebug(): true
{
return true;
}






private function ensureXdebugIsAvailable(): void
{
if (!extension_loaded('xdebug')) {
throw new XdebugNotAvailableException;
}

if (!version_compare(phpversion('xdebug'), '3.1', '>=')) {
throw new XdebugVersionNotSupportedException(phpversion('xdebug'));
}

if (!in_array('coverage', xdebug_info('mode'), true)) {
throw new XdebugNotEnabledException;
}
}
}
