<?php declare(strict_types=1);








namespace PHPUnit\Metadata;

/**
@immutable
@no-named-arguments

*/
final readonly class UsesNamespace extends Metadata
{



private string $namespace;





protected function __construct(int $level, string $namespace)
{
parent::__construct($level);

$this->namespace = $namespace;
}

public function isUsesNamespace(): true
{
return true;
}




public function namespace(): string
{
return $this->namespace;
}
}
