<?php declare(strict_types=1);








namespace PHPUnit\Framework\Attributes;

use Attribute;

/**
@immutable
@no-named-arguments

*/
#[Attribute(Attribute::TARGET_METHOD)]
final readonly class IgnorePhpunitWarnings
{

private ?string $messagePattern;




public function __construct(null|string $messagePattern = null)
{
$this->messagePattern = $messagePattern;
}




public function messagePattern(): ?string
{
return $this->messagePattern;
}
}
