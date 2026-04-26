<?php










declare(strict_types=1);

namespace phpDocumentor\Reflection\DocBlock\Tags\Factory;

use phpDocumentor\Reflection\DocBlock\Tag;
use phpDocumentor\Reflection\DocBlock\Tags\InvalidTag;
use phpDocumentor\Reflection\Types\Context as TypeContext;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\ParserException;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use RuntimeException;

use function property_exists;
use function rtrim;
use function str_replace;
use function trim;









class AbstractPHPStanFactory implements Factory
{
private PhpDocParser $parser;
private Lexer $lexer;

private array $factories;

public function __construct(PHPStanFactory ...$factories)
{
$config = new ParserConfig(['indexes' => true, 'lines' => true]);
$this->lexer = new Lexer($config);
$constParser = new ConstExprParser($config);
$this->parser = new PhpDocParser(
$config,
new TypeParser($config, $constParser),
$constParser
);

$this->factories = $factories;
}

public function create(string $tagLine, ?TypeContext $context = null): Tag
{
try {
$tokens = $this->tokenizeLine($tagLine);
$ast = $this->parser->parseTag($tokens);
if (property_exists($ast->value, 'description') === true) {
$ast->value->setAttribute(
'description',
rtrim($ast->value->description . $tokens->joinUntil(Lexer::TOKEN_END), "\n")
);
}
} catch (ParserException $e) {
return InvalidTag::create($tagLine, '')->withError($e);
}

if ($context === null) {
$context = new TypeContext('');
}

try {
foreach ($this->factories as $factory) {
if ($factory->supports($ast, $context)) {
return $factory->create($ast, $context);
}
}
} catch (RuntimeException $e) {
return InvalidTag::create((string) $ast->value, 'method')->withError($e);
} catch (ParserException $e) {
return InvalidTag::create((string) $ast->value, $ast->name)->withError($e);
}

return InvalidTag::create(
(string) $ast->value,
$ast->name
);
}








private function tokenizeLine(string $tagLine): TokenIterator
{

$tagLine = str_replace("\n", "\n* ", $tagLine);
$tokens = $this->lexer->tokenize($tagLine . "\n");
$fixed = [];
foreach ($tokens as $token) {
if ($token[Lexer::TYPE_OFFSET] === Lexer::TOKEN_PHPDOC_EOL) {


$fixed[] = [
Lexer::VALUE_OFFSET => trim($token[Lexer::VALUE_OFFSET], "* \t"),
Lexer::TYPE_OFFSET => $token[Lexer::TYPE_OFFSET],
Lexer::LINE_OFFSET => $token[Lexer::LINE_OFFSET] ?? 0,
];

continue;
}

$fixed[] = $token;
}

return new TokenIterator($fixed);
}
}
