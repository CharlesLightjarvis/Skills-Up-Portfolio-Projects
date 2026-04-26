<?php declare(strict_types=1);








namespace PHPUnit\Runner\DeprecationCollector;

use PHPUnit\Event\Test\DeprecationTriggered;
use PHPUnit\TestRunner\IssueFilter;

/**
@no-named-arguments


*/
final class InIsolationCollector
{
private readonly IssueFilter $issueFilter;




private array $deprecations = [];




private array $filteredDeprecations = [];

public function __construct(IssueFilter $issueFilter)
{
$this->issueFilter = $issueFilter;
}




public function deprecations(): array
{
return $this->deprecations;
}




public function filteredDeprecations(): array
{
return $this->filteredDeprecations;
}

public function testTriggeredDeprecation(DeprecationTriggered $event): void
{
$this->deprecations[] = $event->message();

if (!$this->issueFilter->shouldBeProcessed($event)) {
return;
}

$this->filteredDeprecations[] = $event->message();
}
}
