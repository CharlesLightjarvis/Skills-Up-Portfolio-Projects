<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

/**
@immutable
@no-named-arguments

*/
final class Function_ extends Target
{



private string $functionName;




protected function __construct(string $functionName)
{
$this->functionName = $functionName;
}

public function isFunction(): true
{
return true;
}




public function functionName(): string
{
return $this->functionName;
}




public function key(): string
{
return 'functions';
}




public function target(): string
{
return $this->functionName;
}




public function description(): string
{
return 'Function ' . $this->target();
}
}
