<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\StaticAnalysis;

/**
@phpstan-type


*/
final readonly class AnalysisResult
{



private array $interfaces;




private array $classes;




private array $traits;




private array $functions;
private LinesOfCode $linesOfCode;




private array $executableLines;




private array $ignoredLines;









public function __construct(array $interfaces, array $classes, array $traits, array $functions, LinesOfCode $linesOfCode, array $executableLines, array $ignoredLines)
{
$this->interfaces = $interfaces;
$this->classes = $classes;
$this->traits = $traits;
$this->functions = $functions;
$this->linesOfCode = $linesOfCode;
$this->executableLines = $executableLines;
$this->ignoredLines = $ignoredLines;
}




public function interfaces(): array
{
return $this->interfaces;
}




public function classes(): array
{
return $this->classes;
}




public function traits(): array
{
return $this->traits;
}




public function functions(): array
{
return $this->functions;
}

public function linesOfCode(): LinesOfCode
{
return $this->linesOfCode;
}




public function executableLines(): array
{
return $this->executableLines;
}




public function ignoredLines(): array
{
return $this->ignoredLines;
}
}
