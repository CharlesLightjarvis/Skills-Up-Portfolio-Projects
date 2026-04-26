<?php

namespace Illuminate\Database\Events;

class ModelPruningFinished
{





public function __construct(
public $models,
) {
}
}
