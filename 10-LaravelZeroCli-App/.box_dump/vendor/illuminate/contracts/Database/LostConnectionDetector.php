<?php

namespace Illuminate\Contracts\Database;

use Throwable;

interface LostConnectionDetector
{






public function causedByLostConnection(Throwable $e): bool;
}
