<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject\Generator;

use SebastianBergmann\Type\Type;

/**
@no-named-arguments


*/
final readonly class HookedProperty
{



private string $name;
private Type $type;
private bool $getHook;
private bool $setHook;
private bool $virtual;
private ?Type $setterType;




public function __construct(string $name, Type $type, bool $getHook, bool $setHook, bool $virtual, ?Type $setterType)
{
$this->name = $name;
$this->type = $type;
$this->getHook = $getHook;
$this->setHook = $setHook;
$this->virtual = $virtual;
$this->setterType = $setterType;
}

public function name(): string
{
return $this->name;
}

public function type(): Type
{
return $this->type;
}

public function hasGetHook(): bool
{
return $this->getHook;
}

public function hasSetHook(): bool
{
return $this->setHook;
}

public function shouldGenerateGetHook(): bool
{
return $this->getHook || !$this->virtual && $this->setHook;
}

public function shouldGenerateSetHook(): bool
{
return $this->setHook || !$this->virtual && $this->getHook;
}

public function setterType(): ?Type
{
return $this->setterType;
}
}
