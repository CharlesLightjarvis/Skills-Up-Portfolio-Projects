<?php

namespace Illuminate\Bus\Events;

use Illuminate\Bus\Batch;

class BatchFinished
{





public function __construct(
public Batch $batch,
) {
}
}
