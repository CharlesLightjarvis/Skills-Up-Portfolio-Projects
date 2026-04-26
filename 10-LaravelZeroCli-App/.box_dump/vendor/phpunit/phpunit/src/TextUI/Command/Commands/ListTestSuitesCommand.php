<?php declare(strict_types=1);








namespace PHPUnit\TextUI\Command;

use const PHP_EOL;
use function assert;
use function count;
use function ksort;
use function sprintf;
use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\Configuration\Registry;

/**
@no-named-arguments


*/
final readonly class ListTestSuitesCommand implements Command
{
private TestSuite $testSuite;

public function __construct(TestSuite $testSuite)
{
$this->testSuite = $testSuite;
}

public function execute(): Result
{

$suites = [];

foreach ($this->testSuite->tests() as $test) {
assert($test instanceof TestSuite);

$suites[$test->name()] = count($test->collect());
}

ksort($suites);

$buffer = $this->warnAboutConflictingOptions();

$buffer .= sprintf(
'Available test suite%s:' . PHP_EOL,
count($suites) > 1 ? 's' : '',
);

foreach ($suites as $suite => $numberOfTests) {
$buffer .= sprintf(
' - %s (%d test%s)' . PHP_EOL,
$suite,
$numberOfTests,
$numberOfTests > 1 ? 's' : '',
);
}

return Result::from($buffer);
}

private function warnAboutConflictingOptions(): string
{
$buffer = '';

$configuration = Registry::get();

if ($configuration->includeTestSuites() !== [] && !$configuration->hasDefaultTestSuite()) {
$buffer .= 'The --testsuite and --list-suites options cannot be combined, --testsuite is ignored' . PHP_EOL;
}

if ($configuration->hasFilter()) {
$buffer .= 'The --filter and --list-suites options cannot be combined, --filter is ignored' . PHP_EOL;
}

if ($configuration->hasGroups()) {
$buffer .= 'The --group (CLI) and <groups> (XML) options cannot be combined with --list-suites, --group and <groups> are ignored' . PHP_EOL;
}

if ($configuration->hasExcludeGroups()) {
$buffer .= 'The --exclude-group (CLI) and <groups> (XML) options cannot be combined with --list-suites, --exclude-group and <groups> are ignored' . PHP_EOL;
}

if ($buffer !== '') {
$buffer .= PHP_EOL;
}

return $buffer;
}
}
