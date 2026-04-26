<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\StaticAnalysis;




final readonly class Interface_
{



private string $name;




private string $namespacedName;
private string $namespace;




private int $startLine;




private int $endLine;




private array $parentInterfaces;








public function __construct(string $name, string $namespacedName, string $namespace, int $startLine, int $endLine, array $parentInterfaces)
{
$this->name = $name;
$this->namespacedName = $namespacedName;
$this->namespace = $namespace;
$this->startLine = $startLine;
$this->endLine = $endLine;
$this->parentInterfaces = $parentInterfaces;
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




public function startLine(): int
{
return $this->startLine;
}




public function endLine(): int
{
return $this->endLine;
}




public function parentInterfaces(): array
{
return $this->parentInterfaces;
}
}
