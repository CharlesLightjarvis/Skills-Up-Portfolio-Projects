<?php declare(strict_types=1);








namespace PHPUnit\Metadata;

/**
@immutable
@no-named-arguments

*/
final readonly class CoversClassesThatImplementInterface extends Metadata
{



private string $interfaceName;





protected function __construct(int $level, string $interfaceName)
{
parent::__construct($level);

$this->interfaceName = $interfaceName;
}

public function isCoversClassesThatImplementInterface(): true
{
return true;
}




public function interfaceName(): string
{
return $this->interfaceName;
}
}
