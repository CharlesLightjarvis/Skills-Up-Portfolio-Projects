<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\StaticAnalysis;




final readonly class Method
{



private string $name;




private int $startLine;




private int $endLine;
private Visibility $visibility;




private string $signature;




private int $cyclomaticComplexity;








public function __construct(string $name, int $startLine, int $endLine, string $signature, Visibility $visibility, int $cyclomaticComplexity)
{
$this->name = $name;
$this->startLine = $startLine;
$this->endLine = $endLine;
$this->signature = $signature;
$this->visibility = $visibility;
$this->cyclomaticComplexity = $cyclomaticComplexity;
}




public function name(): string
{
return $this->name;
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

public function visibility(): Visibility
{
return $this->visibility;
}




public function cyclomaticComplexity(): int
{
return $this->cyclomaticComplexity;
}
}
