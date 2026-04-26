<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\DocBlock\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\Type;




class Extends_ extends TagWithType
{
public function __construct(Type $type, ?Description $description = null)
{
$this->name = 'extends';
$this->type = $type;
$this->description = $description;
}
}
