<?php

namespace Illuminate\Database\Events;

use Illuminate\Contracts\Database\Events\MigrationEvent;

class MigrationSkipped implements MigrationEvent
{





public function __construct(
public $migrationName,
) {
}
}
