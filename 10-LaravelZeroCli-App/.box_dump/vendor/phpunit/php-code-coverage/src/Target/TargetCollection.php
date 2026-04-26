<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

use function count;
use Countable;
use IteratorAggregate;

/**
@template-implements
@immutable
@no-named-arguments


*/
final readonly class TargetCollection implements Countable, IteratorAggregate
{



private array $targets;




public static function fromArray(array $targets): self
{
return new self(...$targets);
}

private function __construct(Target ...$targets)
{
$this->targets = $targets;
}




public function asArray(): array
{
return $this->targets;
}

public function count(): int
{
return count($this->targets);
}

public function isEmpty(): bool
{
return $this->count() === 0;
}

public function isNotEmpty(): bool
{
return $this->count() > 0;
}

public function getIterator(): TargetCollectionIterator
{
return new TargetCollectionIterator($this);
}
}
