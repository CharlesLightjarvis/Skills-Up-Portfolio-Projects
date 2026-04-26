<?php

namespace Illuminate\Database\Events;

class StatementPrepared
{






public function __construct(
public $connection,
public $statement,
) {
}
}
