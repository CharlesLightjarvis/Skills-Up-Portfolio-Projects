<?php

declare(strict_types=1);

namespace Pest\Profanity\Logging;

use Pest\Profanity\Contracts\Logger;




final class NullLogger implements Logger
{



public function append(string $path, array $profanity): void
{

}




public function output(): void
{

}
}
