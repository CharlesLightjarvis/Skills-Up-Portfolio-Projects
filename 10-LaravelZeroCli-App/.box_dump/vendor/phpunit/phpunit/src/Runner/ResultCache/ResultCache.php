<?php declare(strict_types=1);








namespace PHPUnit\Runner\ResultCache;

use PHPUnit\Framework\TestStatus\TestStatus;

/**
@no-named-arguments


*/
interface ResultCache
{
public function setStatus(ResultCacheId $id, TestStatus $status): void;

public function status(ResultCacheId $id): TestStatus;

public function setTime(ResultCacheId $id, float $time): void;

public function time(ResultCacheId $id): float;

public function load(): void;

public function persist(): void;
}
