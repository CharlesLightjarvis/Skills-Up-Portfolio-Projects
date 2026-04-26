<?php

namespace Illuminate\Database\Events;

class ModelsPruned
{






public function __construct(
public $model,
public $count,
) {
}
}
