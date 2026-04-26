<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

/**
@immutable
@no-named-arguments

*/
final class ClassesThatImplementInterface extends Target
{



private string $interfaceName;




protected function __construct(string $interfaceName)
{
$this->interfaceName = $interfaceName;
}

public function isClassesThatImplementInterface(): true
{
return true;
}




public function interfaceName(): string
{
return $this->interfaceName;
}




public function key(): string
{
return 'classesThatImplementInterface';
}




public function target(): string
{
return $this->interfaceName;
}




public function description(): string
{
return 'Classes that implement interface ' . $this->target();
}
}
