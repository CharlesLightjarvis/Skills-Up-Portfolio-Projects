<?php declare(strict_types=1);








namespace PHPUnit\TextUI\Command;

use const PHP_EOL;
use function printf;
use PHPUnit\TextUI\Configuration\CodeCoverageFilterRegistry;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\TextUI\Configuration\NoCoverageCacheDirectoryException;
use SebastianBergmann\CodeCoverage\StaticAnalysis\CacheWarmer;
use SebastianBergmann\Timer\NoActiveTimerException;
use SebastianBergmann\Timer\Timer;

/**
@no-named-arguments




*/
final readonly class WarmCodeCoverageCacheCommand implements Command
{
private Configuration $configuration;
private CodeCoverageFilterRegistry $codeCoverageFilterRegistry;

public function __construct(Configuration $configuration, CodeCoverageFilterRegistry $codeCoverageFilterRegistry)
{
$this->configuration = $configuration;
$this->codeCoverageFilterRegistry = $codeCoverageFilterRegistry;
}





public function execute(): Result
{
if (!$this->configuration->hasCoverageCacheDirectory()) {
return Result::from(
'Cache for static analysis has not been configured' . PHP_EOL,
Result::FAILURE,
);
}

$this->codeCoverageFilterRegistry->init($this->configuration, true);

if (!$this->codeCoverageFilterRegistry->configured()) {
return Result::from(
'Filter for code coverage has not been configured' . PHP_EOL,
Result::FAILURE,
);
}

$timer = new Timer;
$timer->start();

print 'Warming cache for static analysis ... ';

/**
@phpstan-ignore */
$statistics = (new CacheWarmer)->warmCache(
$this->configuration->coverageCacheDirectory(),
!$this->configuration->disableCodeCoverageIgnore(),
$this->configuration->ignoreDeprecatedCodeUnitsFromCodeCoverage(),
$this->codeCoverageFilterRegistry->get(),
);

printf(
'[%s]%s%s%d file%s processed, %d cache hit%s, %d cache miss%s%s',
$timer->stop()->asString(),
PHP_EOL,
PHP_EOL,
$statistics['cacheHits'] + $statistics['cacheMisses'],
($statistics['cacheHits'] + $statistics['cacheMisses']) !== 1 ? 's' : '',
$statistics['cacheHits'],
$statistics['cacheHits'] !== 1 ? 's' : '',
$statistics['cacheMisses'],
$statistics['cacheMisses'] !== 1 ? 'es' : '',
PHP_EOL,
);

return Result::from();
}
}
