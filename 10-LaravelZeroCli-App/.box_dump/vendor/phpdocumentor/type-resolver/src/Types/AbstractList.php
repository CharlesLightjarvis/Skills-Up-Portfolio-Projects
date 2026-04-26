<?php

declare(strict_types=1);










namespace phpDocumentor\Reflection\Types;

use phpDocumentor\Reflection\Type;

/**
@psalm-immutable


*/
abstract class AbstractList implements Type
{

protected $valueType;


protected $keyType;


protected $defaultKeyType;


protected $defaultValueType;




public function __construct(?Type $valueType = null, ?Type $keyType = null)
{
$this->defaultValueType = new Mixed_();
$this->valueType = $valueType;
$this->defaultKeyType = new Compound([new String_(), new Integer()]);
$this->keyType = $keyType;
}

public function getOriginalKeyType(): ?Type
{
return $this->keyType;
}

public function getOriginalValueType(): ?Type
{
return $this->valueType;
}




public function getKeyType(): Type
{
return $this->keyType ?? $this->defaultKeyType;
}




public function getValueType(): Type
{
return $this->valueType ?? $this->defaultValueType;
}
}
