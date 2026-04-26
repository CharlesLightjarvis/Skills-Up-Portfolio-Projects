<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\DocBlock\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\Type;
use Webmozart\Assert\Assert;




final class PropertyWrite extends TagWithType
{
protected string $variableName;

public function __construct(?string $variableName, ?Type $type = null, ?Description $description = null)
{
Assert::string($variableName);

$this->name = 'property-write';
$this->variableName = $variableName;
$this->type = $type;
$this->description = $description;
}




public function getVariableName(): ?string
{
return $this->variableName;
}




public function __toString(): string
{
if ($this->description) {
$description = $this->description->render();
} else {
$description = '';
}

if ($this->variableName) {
$variableName = '$' . $this->variableName;
} else {
$variableName = '';
}

$type = (string) $this->type;

return $type
. ($variableName !== '' ? ($type !== '' ? ' ' : '') . $variableName : '')
. ($description !== '' ? ($type !== '' || $variableName !== '' ? ' ' : '') . $description : '');
}
}
