<?php

namespace Illuminate\Database\Query;

use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Database\Grammar;

/**
@template
*/
class Expression implements ExpressionContract
{





public function __construct(
protected $value,
) {
}







public function getValue(Grammar $grammar)
{
return $this->value;
}
}
