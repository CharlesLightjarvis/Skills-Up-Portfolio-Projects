<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

/**
@immutable
@no-named-arguments

*/
final class Method extends Target
{



private string $className;




private string $methodName;





protected function __construct(string $className, string $methodName)
{
$this->className = $className;
$this->methodName = $methodName;
}

public function isMethod(): true
{
return true;
}




public function className(): string
{
return $this->className;
}




public function methodName(): string
{
return $this->methodName;
}




public function key(): string
{
return 'methods';
}




public function target(): string
{
return $this->className . '::' . $this->methodName;
}




public function description(): string
{
return 'Method ' . $this->target();
}
}
