<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\DocBlock\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\Type;




final class Param extends TagWithType
{
private ?string $variableName = null;


private bool $isVariadic;


private bool $isReference;

public function __construct(
?string $variableName,
?Type $type = null,
bool $isVariadic = false,
?Description $description = null,
bool $isReference = false
) {
$this->name = 'param';
$this->variableName = $variableName;
$this->type = $type;
$this->isVariadic = $isVariadic;
$this->description = $description;
$this->isReference = $isReference;
}




public function getVariableName(): ?string
{
return $this->variableName;
}




public function isVariadic(): bool
{
return $this->isVariadic;
}




public function isReference(): bool
{
return $this->isReference;
}




public function __toString(): string
{
if ($this->description) {
$description = $this->description->render();
} else {
$description = '';
}

$variableName = '';
if ($this->variableName !== null && $this->variableName !== '') {
$variableName .= ($this->isReference ? '&' : '') . ($this->isVariadic ? '...' : '');
$variableName .= '$' . $this->variableName;
}

$type = (string) $this->type;

return $type
. ($variableName !== '' ? ($type !== '' ? ' ' : '') . $variableName : '')
. ($description !== '' ? ($type !== '' || $variableName !== '' ? ' ' : '') . $description : '');
}
}
