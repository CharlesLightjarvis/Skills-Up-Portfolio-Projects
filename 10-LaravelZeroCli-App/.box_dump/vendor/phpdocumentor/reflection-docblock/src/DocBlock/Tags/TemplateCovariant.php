<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\DocBlock\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\Type;




final class TemplateCovariant extends TagWithType
{
public function __construct(Type $type, ?Description $description = null)
{
$this->name = 'template-covariant';
$this->type = $type;
$this->description = $description;
}
}
