<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\DocBlock\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\Tag;
use phpDocumentor\Reflection\Exception\CannotCreateTag;
use phpDocumentor\Reflection\Type;




final class Template extends BaseTag
{

private string $templateName;


private ?Type $bound;

private ?Type $default;


public function __construct(
string $templateName,
?Type $bound = null,
?Type $default = null,
?Description $description = null
) {
$this->name = 'template';
$this->templateName = $templateName;
$this->bound = $bound;
$this->default = $default;
$this->description = $description;
}





public static function create(string $body): ?Tag
{
throw new CannotCreateTag('Template tag cannot be created');
}

public function getTemplateName(): string
{
return $this->templateName;
}

public function getBound(): ?Type
{
return $this->bound;
}

public function getDefault(): ?Type
{
return $this->default;
}

public function __toString(): string
{
$bound = $this->bound !== null ? ' of ' . $this->bound : '';
$default = $this->default !== null ? ' = ' . $this->default : '';

if ($this->description) {
$description = $this->description->render();
} else {
$description = '';
}

return $this->templateName . $bound . $default . ($description !== '' ? ' ' . $description : '');
}
}
