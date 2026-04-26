<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\StaticAnalysis;




interface SourceAnalyser
{



public function analyse(string $sourceCodeFile, string $sourceCode, bool $useAnnotationsForIgnoringCode, bool $ignoreDeprecatedCode): AnalysisResult;
}
