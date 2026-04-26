<?php declare(strict_types=1);








namespace PHPUnit\Runner\ResultCache;

use PHPUnit\Event\Code\Test;
use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Framework\Reorderable;
use PHPUnit\Framework\TestCase;

/**
@no-named-arguments


*/
final readonly class ResultCacheId
{
public static function fromTest(Test $test): self
{
if ($test instanceof TestMethod) {
return new self($test->className() . '::' . $test->name());
}

return new self($test->id());
}

public static function fromReorderable(Reorderable $reorderable): self
{
return new self($reorderable->sortId());
}






public static function fromTestClassAndMethodName(string $class, string $methodName): self
{
return new self($class . '::' . $methodName);
}

private function __construct(
private string $id,
) {
}

public function asString(): string
{
return $this->id;
}
}
