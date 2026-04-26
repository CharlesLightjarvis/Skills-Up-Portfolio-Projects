<?php declare(strict_types=1);








namespace PHPUnit\Metadata;

/**
@immutable
@no-named-arguments

*/
final readonly class IgnoreDeprecations extends Metadata
{

private ?string $messagePattern;





protected function __construct(int $level, null|string $messagePattern)
{
parent::__construct($level);

$this->messagePattern = $messagePattern;
}

public function isIgnoreDeprecations(): true
{
return true;
}




public function messagePattern(): ?string
{
return $this->messagePattern;
}
}
