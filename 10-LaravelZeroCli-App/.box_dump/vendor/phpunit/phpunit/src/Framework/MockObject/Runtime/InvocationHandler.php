<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject;

use function strtolower;
use Exception;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;
use Throwable;

/**
@no-named-arguments


*/
final class InvocationHandler
{



private array $matchers = [];




private array $matcherMap = [];




private readonly array $configurableMethods;
private readonly bool $returnValueGeneration;
private readonly bool $isMockObject;
private ?AssertionFailedError $assertionFailure = null;




public function __construct(array $configurableMethods, bool $returnValueGeneration, bool $isMockObject = false)
{
$this->configurableMethods = $configurableMethods;
$this->returnValueGeneration = $returnValueGeneration;
$this->isMockObject = $isMockObject;
}

public function isMockObject(): bool
{
return $this->isMockObject;
}

public function hasInvocationCountRule(): bool
{
foreach ($this->matchers as $matcher) {
if ($matcher->hasInvocationCountRule()) {
return true;
}
}

return false;
}

public function hasParametersRule(): bool
{
foreach ($this->matchers as $matcher) {
if ($matcher->hasParametersRule()) {
return true;
}
}

return false;
}






public function lookupMatcher(string $id): ?Matcher
{
return $this->matcherMap[$id] ?? null;
}









public function registerMatcher(string $id, Matcher $matcher): void
{
if (isset($this->matcherMap[$id])) {
throw new MatcherAlreadyRegisteredException($id);
}

$this->matcherMap[$id] = $matcher;
}

public function expects(InvocationOrder $rule): InvocationStubber
{
$matcher = new Matcher($rule);
$this->addMatcher($matcher);

return new InvocationStubberImplementation(
$this,
$matcher,
...$this->configurableMethods,
);
}





public function invoke(Invocation $invocation): mixed
{
$exception = null;
$hasReturnValue = false;
$returnValue = null;

foreach ($this->matchers as $match) {
try {
if ($match->matches($invocation)) {
$value = $match->invoked($invocation);

if (!$hasReturnValue) {
$returnValue = $value;
$hasReturnValue = true;
}
}
} catch (Exception $e) {
$exception = $e;

if ($this->assertionFailure === null && $e instanceof AssertionFailedError) {
$this->assertionFailure = $e;
}
}
}

if ($exception !== null) {
throw $exception;
}

if ($hasReturnValue) {
return $returnValue;
}

if (!$this->returnValueGeneration) {
if (strtolower($invocation->methodName()) === '__tostring') {
return '';
}

throw new ReturnValueNotConfiguredException($invocation);
}

return $invocation->generateReturnValue();
}




public function verify(): void
{
foreach ($this->matchers as $matcher) {
$matcher->verify();
}

if ($this->assertionFailure !== null) {
throw $this->assertionFailure;
}
}

private function addMatcher(Matcher $matcher): void
{
$this->matchers[] = $matcher;
}
}
