<?php declare(strict_types=1);








namespace PHPUnit\Runner\ResultCache;

use PHPUnit\Framework\TestStatus\TestStatus;

/**
@no-named-arguments


*/
final readonly class NullResultCache implements ResultCache
{
public function setStatus(ResultCacheId $id, TestStatus $status): void
{
}

public function status(ResultCacheId $id): TestStatus
{
return TestStatus::unknown();
}

public function setTime(ResultCacheId $id, float $time): void
{
}

public function time(ResultCacheId $id): float
{
return 0;
}

public function load(): void
{
}

public function persist(): void
{
}
}
