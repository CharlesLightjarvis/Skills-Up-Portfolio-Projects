<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\StaticAnalysis;

use const T_COMMENT;
use const T_DOC_COMMENT;
use function array_merge;
use function array_unique;
use function assert;
use function is_array;
use function max;
use function range;
use function sort;
use function sprintf;
use function substr_count;
use function token_get_all;
use function trim;
use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use SebastianBergmann\CodeCoverage\ParserException;
use SebastianBergmann\LinesOfCode\LineCountingVisitor;




final readonly class ParsingSourceAnalyser implements SourceAnalyser
{



public function analyse(string $sourceCodeFile, string $sourceCode, bool $useAnnotationsForIgnoringCode, bool $ignoreDeprecatedCode): AnalysisResult
{
$linesOfCode = max(substr_count($sourceCode, "\n") + 1, substr_count($sourceCode, "\r") + 1);

if ($linesOfCode === 0 && $sourceCode !== '') {
$linesOfCode = 1;
}

assert($linesOfCode > 0);

$parser = (new ParserFactory)->createForHostVersion();

try {
$nodes = $parser->parse($sourceCode);

assert($nodes !== null);

$traverser = new NodeTraverser;
$codeUnitFindingVisitor = new CodeUnitFindingVisitor($sourceCodeFile);
$lineCountingVisitor = new LineCountingVisitor($linesOfCode);
$ignoredLinesFindingVisitor = new IgnoredLinesFindingVisitor($useAnnotationsForIgnoringCode, $ignoreDeprecatedCode);
$executableLinesFindingVisitor = new ExecutableLinesFindingVisitor($sourceCode);

$traverser->addVisitor(new NameResolver);
$traverser->addVisitor(new AttributeParentConnectingVisitor);
$traverser->addVisitor($codeUnitFindingVisitor);
$traverser->addVisitor($lineCountingVisitor);
$traverser->addVisitor($ignoredLinesFindingVisitor);
$traverser->addVisitor($executableLinesFindingVisitor);


$traverser->traverse($nodes);

} catch (Error $error) {
throw new ParserException(
sprintf(
'Cannot parse %s: %s',
$sourceCodeFile,
$error->getMessage(),
),
$error->getCode(),
$error,
);
}


$ignoredLines = array_unique(
array_merge(
$this->findLinesIgnoredByLineBasedAnnotations(
$sourceCodeFile,
$sourceCode,
$useAnnotationsForIgnoringCode,
),
$ignoredLinesFindingVisitor->ignoredLines(),
),
);

sort($ignoredLines);

return new AnalysisResult(
$codeUnitFindingVisitor->interfaces(),
$codeUnitFindingVisitor->classes(),
$codeUnitFindingVisitor->traits(),
$codeUnitFindingVisitor->functions(),
new LinesOfCode(
$lineCountingVisitor->result()->linesOfCode(),
$lineCountingVisitor->result()->commentLinesOfCode(),
$lineCountingVisitor->result()->nonCommentLinesOfCode(),
),
$executableLinesFindingVisitor->executableLinesGroupedByBranch(),
$ignoredLines,
);
}




private function findLinesIgnoredByLineBasedAnnotations(string $filename, string $source, bool $useAnnotationsForIgnoringCode): array
{
if (!$useAnnotationsForIgnoringCode) {
return [];
}

$result = [];
$start = false;

foreach (token_get_all($source) as $token) {
if (!is_array($token) ||
!(T_COMMENT === $token[0] || T_DOC_COMMENT === $token[0])) {
continue;
}

$comment = trim($token[1]);

if ($comment === '// @codeCoverageIgnore' ||
$comment === '//@codeCoverageIgnore') {
$result[] = $token[2];

continue;
}

if ($comment === '// @codeCoverageIgnoreStart' ||
$comment === '//@codeCoverageIgnoreStart') {
$start = $token[2];

continue;
}

if ($comment === '// @codeCoverageIgnoreEnd' ||
$comment === '//@codeCoverageIgnoreEnd') {
if (false === $start) {
$start = $token[2];
}

$result = array_merge(
$result,
range($start, $token[2]),
);
}
}

return $result;
}
}
