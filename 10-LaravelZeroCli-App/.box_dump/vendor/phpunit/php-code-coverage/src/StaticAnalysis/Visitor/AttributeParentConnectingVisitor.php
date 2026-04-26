<?php declare(strict_types=1);









namespace SebastianBergmann\CodeCoverage\StaticAnalysis;

use function array_pop;
use function count;
use PhpParser\Node;
use PhpParser\NodeVisitor;









final class AttributeParentConnectingVisitor implements NodeVisitor
{



private array $stack = [];

public function beforeTraverse(array $nodes): null
{
$this->stack = [];

return null;
}

public function enterNode(Node $node): null
{
if ($this->stack !== [] &&
($node instanceof Node\Attribute || $node instanceof Node\AttributeGroup)) {
$node->setAttribute('parent', $this->stack[count($this->stack) - 1]);
}

$this->stack[] = $node;

return null;
}

public function leaveNode(Node $node): null
{
array_pop($this->stack);

return null;
}

public function afterTraverse(array $nodes): null
{
return null;
}
}
