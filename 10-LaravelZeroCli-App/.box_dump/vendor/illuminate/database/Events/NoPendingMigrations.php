<?php

namespace Illuminate\Database\Events;

use Illuminate\Contracts\Database\Events\MigrationEvent;

class NoPendingMigrations implements MigrationEvent
{





public function __construct(
public $method,
) {
}
}
