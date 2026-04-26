<?php declare(strict_types=1);








namespace PHPUnit\Runner\DeprecationCollector;

use PHPUnit\Event\EventFacadeIsSealedException;
use PHPUnit\Event\Facade as EventFacade;
use PHPUnit\Event\UnknownSubscriberTypeException;
use PHPUnit\TestRunner\IssueFilter;
use PHPUnit\TextUI\Configuration\Registry as ConfigurationRegistry;

/**
@no-named-arguments


*/
final class Facade
{
private static null|Collector|InIsolationCollector $collector = null;
private static bool $inIsolation = false;

public static function init(): void
{
self::collector();
}

public static function initForIsolation(): void
{
self::collector();

self::$inIsolation = true;
}




public static function deprecations(): array
{
return self::collector()->deprecations();
}




public static function filteredDeprecations(): array
{
return self::collector()->filteredDeprecations();
}





public static function collector(): Collector|InIsolationCollector
{
if (self::$collector !== null) {
return self::$collector;
}

$issueFilter = new IssueFilter(
ConfigurationRegistry::get()->source(),
);

if (self::$inIsolation) {
self::$collector = new InIsolationCollector(
$issueFilter,
);

return self::$collector;
}

self::$collector = new Collector(
EventFacade::instance(),
$issueFilter,
);

return self::$collector;
}
}
