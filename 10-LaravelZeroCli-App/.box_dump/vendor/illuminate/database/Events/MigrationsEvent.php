<?php

namespace Illuminate\Database\Events;

use Illuminate\Contracts\Database\Events\MigrationEvent as MigrationEventContract;

abstract class MigrationsEvent implements MigrationEventContract
{






public function __construct(
public $method,
public array $options = [],
) {
}
}
