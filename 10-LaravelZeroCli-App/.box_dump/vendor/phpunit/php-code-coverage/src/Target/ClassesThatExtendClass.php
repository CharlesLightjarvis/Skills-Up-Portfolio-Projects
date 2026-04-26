<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

/**
@immutable
@no-named-arguments

*/
final class ClassesThatExtendClass extends Target
{



private string $className;




protected function __construct(string $className)
{
$this->className = $className;
}

public function isClassesThatExtendClass(): true
{
return true;
}




public function className(): string
{
return $this->className;
}




public function key(): string
{
return 'classesThatExtendClass';
}




public function target(): string
{
return $this->className;
}




public function description(): string
{
return 'Classes that extend class ' . $this->target();
}
}
