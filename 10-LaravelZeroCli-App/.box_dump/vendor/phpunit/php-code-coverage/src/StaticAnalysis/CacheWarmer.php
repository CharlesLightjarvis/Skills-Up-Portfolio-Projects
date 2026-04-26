<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\StaticAnalysis;

use function file_get_contents;
use SebastianBergmann\CodeCoverage\Filter;




final readonly class CacheWarmer
{



public function warmCache(string $cacheDirectory, bool $useAnnotationsForIgnoringCode, bool $ignoreDeprecatedCode, Filter $filter): array
{
$analyser = new CachingSourceAnalyser(
$cacheDirectory,
new ParsingSourceAnalyser,
);

foreach ($filter->files() as $file) {
$analyser->analyse(
$file,
file_get_contents($file),
$useAnnotationsForIgnoringCode,
$ignoreDeprecatedCode,
);
}

return [
'cacheHits' => $analyser->cacheHits(),
'cacheMisses' => $analyser->cacheMisses(),
];
}
}
