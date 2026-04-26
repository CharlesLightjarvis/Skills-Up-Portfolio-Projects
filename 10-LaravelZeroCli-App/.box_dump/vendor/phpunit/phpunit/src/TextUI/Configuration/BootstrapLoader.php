<?php declare(strict_types=1);








namespace PHPUnit\TextUI\Configuration;

use const PHP_EOL;
use function in_array;
use function is_readable;
use function sprintf;
use PHPUnit\Event\Facade as EventFacade;
use Throwable;

/**
@no-named-arguments


*/
final class BootstrapLoader
{




public function handle(Configuration $configuration): void
{
if (!$configuration->hasBootstrap()) {
return;
}

$this->load($configuration->bootstrap());

foreach ($configuration->bootstrapForTestSuite() as $testSuiteName => $bootstrapForTestSuite) {
if ($configuration->includeTestSuites() !== [] && !in_array($testSuiteName, $configuration->includeTestSuites(), true)) {
continue;
}

if ($configuration->excludeTestSuites() !== [] && in_array($testSuiteName, $configuration->excludeTestSuites(), true)) {
continue;
}

$this->load($bootstrapForTestSuite);
}
}




private function load(string $filename): void
{
if (!is_readable($filename)) {
throw new BootstrapScriptDoesNotExistException($filename);
}

try {
include_once $filename;
} catch (Throwable $t) {
$message = sprintf(
'Error in bootstrap script: %s:%s%s%s%s',
$t::class,
PHP_EOL,
$t->getMessage(),
PHP_EOL,
$t->getTraceAsString(),
);

while ($t = $t->getPrevious()) {
$message .= sprintf(
'%s%sPrevious error: %s:%s%s%s%s',
PHP_EOL,
PHP_EOL,
$t::class,
PHP_EOL,
$t->getMessage(),
PHP_EOL,
$t->getTraceAsString(),
);
}

throw new BootstrapScriptException($message);
}

EventFacade::emitter()->testRunnerBootstrapFinished($filename);
}
}
