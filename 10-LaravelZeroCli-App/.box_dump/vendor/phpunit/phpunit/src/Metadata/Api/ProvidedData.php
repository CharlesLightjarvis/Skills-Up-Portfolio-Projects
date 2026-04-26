<?php declare(strict_types=1);








namespace PHPUnit\Metadata\Api;

/**
@no-named-arguments


*/
final readonly class ProvidedData
{



private string $label;
private mixed $value;




public function __construct(string $label, mixed $value)
{
$this->label = $label;
$this->value = $value;
}




public function label(): string
{
return $this->label;
}

public function value(): mixed
{
return $this->value;
}
}
