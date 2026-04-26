<?php

namespace Illuminate\Database\Events;

class DatabaseBusy
{






public function __construct(
public $connectionName,
public $connections,
) {
}
}
