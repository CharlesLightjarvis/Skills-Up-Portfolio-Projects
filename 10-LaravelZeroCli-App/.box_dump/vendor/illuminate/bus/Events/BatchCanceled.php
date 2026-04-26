<?php

namespace Illuminate\Bus\Events;

use Illuminate\Bus\Batch;

class BatchCanceled
{





public function __construct(
public Batch $batch,
) {
}
}
