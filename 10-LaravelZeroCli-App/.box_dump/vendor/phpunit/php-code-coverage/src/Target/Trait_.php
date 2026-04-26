<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

/**
@immutable
@no-named-arguments

*/
final class Trait_ extends Target
{



private string $traitName;




protected function __construct(string $traitName)
{
$this->traitName = $traitName;
}

public function isTrait(): true
{
return true;
}




public function traitName(): string
{
return $this->traitName;
}




public function key(): string
{
return 'traits';
}




public function target(): string
{
return $this->traitName;
}




public function description(): string
{
return 'Trait ' . $this->target();
}
}
