<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\StaticAnalysis;

use const DIRECTORY_SEPARATOR;
use function file_get_contents;
use function file_put_contents;
use function hash;
use function implode;
use function is_file;
use function serialize;
use function unserialize;
use SebastianBergmann\CodeCoverage\Util\Filesystem;
use SebastianBergmann\CodeCoverage\Version;




final class CachingSourceAnalyser implements SourceAnalyser
{



private readonly string $directory;
private readonly SourceAnalyser $sourceAnalyser;




private int $cacheHits = 0;




private int $cacheMisses = 0;

public function __construct(string $directory, SourceAnalyser $sourceAnalyser)
{
Filesystem::createDirectory($directory);

$this->directory = $directory;
$this->sourceAnalyser = $sourceAnalyser;
}




public function analyse(string $sourceCodeFile, string $sourceCode, bool $useAnnotationsForIgnoringCode, bool $ignoreDeprecatedCode): AnalysisResult
{
$cacheFile = $this->cacheFile(
$sourceCode,
$useAnnotationsForIgnoringCode,
$ignoreDeprecatedCode,
);

$cachedAnalysisResult = $this->read($cacheFile);

if ($cachedAnalysisResult !== false) {
$this->cacheHits++;

return $cachedAnalysisResult;
}

$this->cacheMisses++;

$analysisResult = $this->sourceAnalyser->analyse(
$sourceCodeFile,
$sourceCode,
$useAnnotationsForIgnoringCode,
$ignoreDeprecatedCode,
);

$this->write($cacheFile, $analysisResult);

return $analysisResult;
}




public function cacheHits(): int
{
return $this->cacheHits;
}




public function cacheMisses(): int
{
return $this->cacheMisses;
}




private function read(string $cacheFile): AnalysisResult|false
{
if (!is_file($cacheFile)) {
return false;
}

return unserialize(
file_get_contents($cacheFile),
[
'allowed_classes' => [
AnalysisResult::class,
Class_::class,
Function_::class,
Interface_::class,
LinesOfCode::class,
Method::class,
Trait_::class,
],
],
);
}




private function write(string $cacheFile, AnalysisResult $result): void
{
file_put_contents(
$cacheFile,
serialize($result),
);
}

private function cacheFile(string $source, bool $useAnnotationsForIgnoringCode, bool $ignoreDeprecatedCode): string
{
$cacheKey = hash(
'sha256',
implode(
"\0",
[
$source,
Version::id(),
$useAnnotationsForIgnoringCode,
$ignoreDeprecatedCode,
],
),
);

return $this->directory . DIRECTORY_SEPARATOR . $cacheKey;
}
}
