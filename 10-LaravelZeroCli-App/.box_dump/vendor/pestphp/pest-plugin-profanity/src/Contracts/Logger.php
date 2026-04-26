<?php

declare(strict_types=1);

namespace Pest\Profanity\Contracts;




interface Logger
{
public function append(string $path, array $profanity): void;




public function output(): void;
}
