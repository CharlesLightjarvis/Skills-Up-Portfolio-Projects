<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\StaticAnalysis;

use function file_get_contents;




final class FileAnalyser
{
private readonly SourceAnalyser $sourceAnalyser;
private readonly bool $useAnnotationsForIgnoringCode;
private readonly bool $ignoreDeprecatedCode;




private array $cache = [];

public function __construct(SourceAnalyser $sourceAnalyser, bool $useAnnotationsForIgnoringCode, bool $ignoreDeprecatedCode)
{
$this->sourceAnalyser = $sourceAnalyser;
$this->useAnnotationsForIgnoringCode = $useAnnotationsForIgnoringCode;
$this->ignoreDeprecatedCode = $ignoreDeprecatedCode;
}




public function analyse(string $sourceCodeFile): AnalysisResult
{
if (isset($this->cache[$sourceCodeFile])) {
return $this->cache[$sourceCodeFile];
}

$this->cache[$sourceCodeFile] = $this->sourceAnalyser->analyse(
$sourceCodeFile,
file_get_contents($sourceCodeFile),
$this->useAnnotationsForIgnoringCode,
$this->ignoreDeprecatedCode,
);

return $this->cache[$sourceCodeFile];
}
}
