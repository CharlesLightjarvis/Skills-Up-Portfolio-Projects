<?php

namespace Illuminate\Cache\Events;

use Throwable;

class CacheFailedOver
{






public function __construct(
public ?string $storeName,
public Throwable $exception,
) {
}
}
