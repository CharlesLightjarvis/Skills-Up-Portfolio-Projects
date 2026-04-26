<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\StaticAnalysis;




final readonly class Class_
{



private string $name;




private string $namespacedName;
private string $namespace;




private string $file;




private int $startLine;




private int $endLine;




private ?string $parentClass;




private array $interfaces;




private array $traits;




private array $methods;












public function __construct(string $name, string $namespacedName, string $namespace, string $file, int $startLine, int $endLine, ?string $parentClass, array $interfaces, array $traits, array $methods)
{
$this->name = $name;
$this->namespacedName = $namespacedName;
$this->namespace = $namespace;
$this->file = $file;
$this->startLine = $startLine;
$this->endLine = $endLine;
$this->parentClass = $parentClass;
$this->interfaces = $interfaces;
$this->traits = $traits;
$this->methods = $methods;
}




public function name(): string
{
return $this->name;
}




public function namespacedName(): string
{
return $this->namespacedName;
}

public function isNamespaced(): bool
{
return $this->namespace !== '';
}

public function namespace(): string
{
return $this->namespace;
}




public function file(): string
{
return $this->file;
}




public function startLine(): int
{
return $this->startLine;
}




public function endLine(): int
{
return $this->endLine;
}

public function hasParent(): bool
{
return $this->parentClass !== null;
}




public function parentClass(): ?string
{
return $this->parentClass;
}




public function interfaces(): array
{
return $this->interfaces;
}




public function traits(): array
{
return $this->traits;
}




public function methods(): array
{
return $this->methods;
}
}
