<?php

namespace Illuminate\Contracts\Database;

use Throwable;

interface ConcurrencyErrorDetector
{






public function causedByConcurrencyError(Throwable $e): bool;
}
