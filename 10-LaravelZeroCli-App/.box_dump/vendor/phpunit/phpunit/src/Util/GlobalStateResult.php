<?php declare(strict_types=1);








namespace PHPUnit\Util;

/**
@immutable
@no-named-arguments



*/
final readonly class GlobalStateResult
{



private string $globalsString;




private array $skippedGlobals;




public function __construct(string $globalsString, array $skippedGlobals)
{
$this->globalsString = $globalsString;
$this->skippedGlobals = $skippedGlobals;
}

public function globalsString(): string
{
return $this->globalsString;
}




public function skippedGlobals(): array
{
return $this->skippedGlobals;
}

public function hasSkippedGlobals(): bool
{
return $this->skippedGlobals !== [];
}
}
