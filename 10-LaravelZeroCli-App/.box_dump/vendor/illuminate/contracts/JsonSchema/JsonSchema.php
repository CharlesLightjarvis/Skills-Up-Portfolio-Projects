<?php

namespace Illuminate\Contracts\JsonSchema;

use Closure;

interface JsonSchema
{






public function object(Closure|array $properties = []);






public function array();






public function string();






public function integer();






public function number();






public function boolean();
}
