<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

/**
@immutable
@no-named-arguments

*/
final class Namespace_ extends Target
{



private string $namespace;




protected function __construct(string $namespace)
{
$this->namespace = $namespace;
}

public function isNamespace(): true
{
return true;
}




public function namespace(): string
{
return $this->namespace;
}




public function key(): string
{
return 'namespaces';
}




public function target(): string
{
return $this->namespace;
}




public function description(): string
{
return 'Namespace ' . $this->target();
}
}
