<?php

declare(strict_types=1);

namespace Pest\Concerns;

use Closure;
use Pest\Exceptions\DatasetArgumentsMismatch;
use Pest\Panic;
use Pest\Preset;
use Pest\Support\ChainableClosure;
use Pest\Support\ExceptionTrace;
use Pest\Support\Reflection;
use Pest\Support\Shell;
use Pest\TestSuite;
use PHPUnit\Framework\Attributes\PostCondition;
use PHPUnit\Framework\IncompleteTest;
use PHPUnit\Framework\SkippedTest;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionFunction;
use ReflectionParameter;
use Throwable;

/**
@mixin


*/
trait Testable
{



private string $__description;




private static string $__latestDescription;




private static array $__latestAssignees = [];




private static array $__latestNotes = [];






private static array $__latestIssues = [];






private static array $__latestPrs = [];






public array $__describing = [];




public bool $__ran = false;




private Closure $__test;




private ?Closure $__beforeEach = null;




private ?Closure $__afterEach = null;




private static ?Closure $__beforeAll = null;




private static ?Closure $__afterAll = null;




private array $__snapshotChanges = [];




public static function flush(): void
{
self::$__beforeAll = null;
self::$__afterAll = null;
}




public function note(array|string $note): self
{
$note = is_array($note) ? $note : [$note];

self::$__latestNotes = array_merge(self::$__latestNotes, $note);

return $this;
}




public function __addBeforeAll(?Closure $hook): void
{
if (! $hook instanceof Closure) {
return;
}

self::$__beforeAll = (self::$__beforeAll instanceof Closure)
? ChainableClosure::boundStatically(self::$__beforeAll, $hook)
: $hook;
}




public function __addAfterAll(?Closure $hook): void
{
if (! $hook instanceof Closure) {
return;
}

self::$__afterAll = (self::$__afterAll instanceof Closure)
? ChainableClosure::boundStatically(self::$__afterAll, $hook)
: $hook;
}




public function __addBeforeEach(?Closure $hook): void
{
$this->__addHook('__beforeEach', $hook);
}




public function __addAfterEach(?Closure $hook): void
{
$this->__addHook('__afterEach', $hook);
}




private function __addHook(string $property, ?Closure $hook): void
{
if (! $hook instanceof Closure) {
return;
}

$this->{$property} = ($this->{$property} instanceof Closure)
? ChainableClosure::bound($this->{$property}, $hook)
: $hook;
}




public static function setUpBeforeClass(): void
{
parent::setUpBeforeClass();

$beforeAll = TestSuite::getInstance()->beforeAll->get(self::$__filename);

if (self::$__beforeAll instanceof Closure) {
$beforeAll = ChainableClosure::boundStatically(self::$__beforeAll, $beforeAll);
}

try {
call_user_func(Closure::bind($beforeAll, null, self::class));
} catch (Throwable $e) {
Panic::with($e);
}
}




public static function tearDownAfterClass(): void
{
$afterAll = TestSuite::getInstance()->afterAll->get(self::$__filename);

if (self::$__afterAll instanceof Closure) {
$afterAll = ChainableClosure::boundStatically(self::$__afterAll, $afterAll);
}

call_user_func(Closure::bind($afterAll, null, self::class));

parent::tearDownAfterClass();
}




protected function setUp(...$arguments): void
{
TestSuite::getInstance()->test = $this;

$method = TestSuite::getInstance()->tests->get(self::$__filename)->getMethod($this->name());

$description = $method->description;
if ($this->dataName()) {
$description = str_contains((string) $description, ':dataset')
? str_replace(':dataset', str_replace('dataset ', '', $this->dataName()), (string) $description)
: $description.' with '.$this->dataName();
}

$description = htmlspecialchars(html_entity_decode((string) $description), ENT_NOQUOTES);

if ($method->repetitions > 1) {
$matches = [];
preg_match('/\((.*?)\)/', $description, $matches);

if (count($matches) > 1) {
if (str_contains($description, 'with '.$matches[0].' /')) {
$description = str_replace('with '.$matches[0].' /', '', $description);
} else {
$description = str_replace('with '.$matches[0], '', $description);
}
}

$description .= ' @ repetition '.($matches[1].' of '.$method->repetitions);
}

$this->__description = self::$__latestDescription = $description;
self::$__latestAssignees = $method->assignees;
self::$__latestNotes = $method->notes;
self::$__latestIssues = $method->issues;
self::$__latestPrs = $method->prs;

parent::setUp();

$beforeEach = TestSuite::getInstance()->beforeEach->get(self::$__filename)[1];

if ($this->__beforeEach instanceof Closure) {
$beforeEach = ChainableClosure::bound($this->__beforeEach, $beforeEach);
}

$this->__callClosure($beforeEach, $arguments);
}




public function __initializeTestCase(): void
{

if (isset($this->__test)) {
return;
}

$name = $this->name();
$test = TestSuite::getInstance()->tests->get(self::$__filename);

if ($test->hasMethod($name)) {
$method = $test->getMethod($name);
$this->__description = self::$__latestDescription = $method->description;
self::$__latestAssignees = $method->assignees;
self::$__latestNotes = $method->notes;
self::$__latestIssues = $method->issues;
self::$__latestPrs = $method->prs;
$this->__describing = $method->describing;
$this->__test = $method->getClosure();

$method->setUp($this);
}
}




protected function tearDown(...$arguments): void
{
$afterEach = TestSuite::getInstance()->afterEach->get(self::$__filename);

if ($this->__afterEach instanceof Closure) {
$afterEach = ChainableClosure::bound($this->__afterEach, $afterEach);
}

try {
$this->__callClosure($afterEach, func_get_args());
} finally {
parent::tearDown();

TestSuite::getInstance()->test = null;

$method = TestSuite::getInstance()->tests->get(self::$__filename)->getMethod($this->name());
$method->tearDown($this);
}
}






private function __runTest(Closure $closure, ...$args): mixed
{
$arguments = $this->__resolveTestArguments($args);
$this->__ensureDatasetArgumentNameAndNumberMatches($arguments);

$method = TestSuite::getInstance()->tests->get(self::$__filename)->getMethod($this->name());

if ($method->flakyTries === null) {
return $this->__callClosure($closure, $arguments);
}

$lastException = null;
$initialProperties = get_object_vars($this);

for ($attempt = 1; $attempt <= $method->flakyTries; $attempt++) {
try {
return $this->__callClosure($closure, $arguments);
} catch (Throwable $e) {
if ($e instanceof SkippedTest
|| $e instanceof IncompleteTest
|| $this->__isExpectedException($e)) {
throw $e;
}

$lastException = $e;

if ($attempt < $method->flakyTries) {
if ($this->__snapshotChanges !== []) {
throw $e;
}

$this->tearDown();

Closure::bind(fn (): array => $this->mockObjects = [], $this, TestCase::class)();

foreach (array_keys(array_diff_key(get_object_vars($this), $initialProperties)) as $property) {
unset($this->{$property});
}

$hasOutputExpectation = Closure::bind(fn (): bool => is_string($this->outputExpectedString) || is_string($this->outputExpectedRegex), $this, TestCase::class)();

if ($hasOutputExpectation) {
ob_clean();
}

$this->setUp();
}
}
}

throw $lastException;
}




private function __isExpectedException(Throwable $e): bool
{
$read = fn (string $property): mixed => Closure::bind(fn () => $this->{$property}, $this, TestCase::class)();

$expectedClass = $read('expectedException');

if ($expectedClass !== null) {
return $e instanceof $expectedClass;
}

$expectedMessage = $read('expectedExceptionMessage');

if ($expectedMessage !== null) {
return str_contains($e->getMessage(), (string) $expectedMessage);
}

$expectedCode = $read('expectedExceptionCode');

if ($expectedCode !== null) {
return $e->getCode() === $expectedCode;
}

return false;
}






private function __resolveTestArguments(array $arguments): array
{
$method = TestSuite::getInstance()->tests->get(self::$__filename)->getMethod($this->name());

if ($method->repetitions > 1) {




$firstArgument = array_shift($arguments);
$arguments[] = $firstArgument;
}

$underlyingTest = Reflection::getFunctionVariable($this->__test, 'closure');
$testParameterTypesByName = Reflection::getFunctionArguments($underlyingTest);
$testParameterTypes = array_values($testParameterTypesByName);

if (count($arguments) !== 1) {
foreach ($arguments as $argumentIndex => $argumentValue) {
if (! $argumentValue instanceof Closure) {
continue;
}

$parameterType = is_string($argumentIndex)
? $testParameterTypesByName[$argumentIndex]
: $testParameterTypes[$argumentIndex];

if (in_array($parameterType, [Closure::class, 'callable', 'mixed'])) {
continue;
}

$arguments[$argumentIndex] = $this->__callClosure($argumentValue, []);
}

return $arguments;
}

if (! isset($arguments[0]) || ! $arguments[0] instanceof Closure) {
return $arguments;
}

if (isset($testParameterTypes[0]) && in_array($testParameterTypes[0], [Closure::class, 'callable'])) {
return $arguments;
}

$boundDatasetResult = $this->__callClosure($arguments[0], []);
if (count($testParameterTypes) === 1) {
return [$boundDatasetResult];
}
if (! is_array($boundDatasetResult)) {
return [$boundDatasetResult];
}

return $boundDatasetResult;
}







private function __ensureDatasetArgumentNameAndNumberMatches(array $arguments): void
{
if ($arguments === []) {
return;
}

$underlyingTest = Reflection::getFunctionVariable($this->__test, 'closure');
$testReflection = new ReflectionFunction($underlyingTest);
$requiredParametersCount = $testReflection->getNumberOfRequiredParameters();
$suppliedParametersCount = count($arguments);

$datasetParameterNames = array_keys($arguments);
$testParameterNames = array_map(
fn (ReflectionParameter $reflectionParameter): string => $reflectionParameter->getName(),
array_filter($testReflection->getParameters(), fn (ReflectionParameter $reflectionParameter): bool => ! $reflectionParameter->isOptional()),
);

if (array_diff($testParameterNames, $datasetParameterNames) === []) {
return;
}

if (isset($testParameterNames[0]) && $suppliedParametersCount >= $requiredParametersCount) {
return;
}

throw new DatasetArgumentsMismatch($requiredParametersCount, $suppliedParametersCount);
}




private function __callClosure(Closure $closure, array $arguments): mixed
{
return ExceptionTrace::ensure(fn (): mixed => call_user_func_array(Closure::bind($closure, $this, $this::class), $arguments));
}




public function preset(): Preset
{
return new Preset;
}

#[PostCondition]
protected function __MarkTestIncompleteIfSnapshotHaveChanged(): void
{
if (count($this->__snapshotChanges) === 0) {
return;
}

$this->markTestIncomplete(implode('. ', $this->__snapshotChanges));
}




public static function getPrintableTestCaseName(): string
{
return preg_replace('/P\\\/', '', self::class, 1);
}




public function getPrintableTestCaseMethodName(): string
{
return $this->__description;
}




public static function getLatestPrintableTestCaseMethodName(): string
{
return self::$__latestDescription ?? '';
}




public static function getPrintableContext(): array
{
return [
'assignees' => self::$__latestAssignees,
'issues' => self::$__latestIssues,
'prs' => self::$__latestPrs,
'notes' => self::$__latestNotes,
];
}




public function shell(): void
{
Shell::open();
}
}
