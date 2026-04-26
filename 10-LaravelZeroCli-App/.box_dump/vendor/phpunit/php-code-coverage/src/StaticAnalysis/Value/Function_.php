<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\StaticAnalysis;




final readonly class Function_
{



private string $name;




private string $namespacedName;
private string $namespace;




private int $startLine;




private int $endLine;




private string $signature;




private int $cyclomaticComplexity;









public function __construct(string $name, string $namespacedName, string $namespace, int $startLine, int $endLine, string $signature, int $cyclomaticComplexity)
{
$this->name = $name;
$this->namespacedName = $namespacedName;
$this->namespace = $namespace;
$this->startLine = $startLine;
$this->endLine = $endLine;
$this->signature = $signature;
$this->cyclomaticComplexity = $cyclomaticComplexity;
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




public function signature(): string
{
return $this->signature;
}




public function cyclomaticComplexity(): int
{
return $this->cyclomaticComplexity;
}
}
