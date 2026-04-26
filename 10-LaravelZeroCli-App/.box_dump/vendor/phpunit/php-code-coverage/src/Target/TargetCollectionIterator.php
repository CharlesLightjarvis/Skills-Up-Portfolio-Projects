<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

use function count;
use Iterator;

/**
@template-implements
@no-named-arguments

*/
final class TargetCollectionIterator implements Iterator
{



private readonly array $targets;
private int $position = 0;

public function __construct(TargetCollection $metadata)
{
$this->targets = $metadata->asArray();
}

public function rewind(): void
{
$this->position = 0;
}

public function valid(): bool
{
return $this->position < count($this->targets);
}

public function key(): int
{
return $this->position;
}

public function current(): Target
{
return $this->targets[$this->position];
}

public function next(): void
{
$this->position++;
}
}
