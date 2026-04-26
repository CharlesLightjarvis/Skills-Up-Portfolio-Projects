<?php

namespace Illuminate\Database\Events;

class ModelPruningStarting
{





public function __construct(
public $models,
) {
}
}
