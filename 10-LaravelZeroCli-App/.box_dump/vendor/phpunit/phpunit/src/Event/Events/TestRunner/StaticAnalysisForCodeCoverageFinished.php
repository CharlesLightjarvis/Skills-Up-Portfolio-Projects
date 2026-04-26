<?php declare(strict_types=1);








namespace PHPUnit\Event\TestRunner;

use function sprintf;
use PHPUnit\Event\Event;
use PHPUnit\Event\Telemetry;

/**
@immutable
@no-named-arguments

*/
final readonly class StaticAnalysisForCodeCoverageFinished implements Event
{
private Telemetry\Info $telemetryInfo;




private int $cacheHits;




private int $cacheMisses;





public function __construct(Telemetry\Info $telemetryInfo, int $cacheHits, int $cacheMisses)
{
$this->telemetryInfo = $telemetryInfo;
$this->cacheHits = $cacheHits;
$this->cacheMisses = $cacheMisses;
}

public function telemetryInfo(): Telemetry\Info
{
return $this->telemetryInfo;
}




public function cacheHits(): int
{
return $this->cacheHits;
}




public function cacheMisses(): int
{
return $this->cacheMisses;
}




public function asString(): string
{
return sprintf(
'Static Analysis for Code Coverage Finished (%d cache hits, %d cache misses)',
$this->cacheHits,
$this->cacheMisses,
);
}
}
