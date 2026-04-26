<?php

declare(strict_types=1);

namespace Pest\Profanity;




final class Result
{





public function __construct(
public readonly string $file,
public readonly array $errors,
) {

}
}
