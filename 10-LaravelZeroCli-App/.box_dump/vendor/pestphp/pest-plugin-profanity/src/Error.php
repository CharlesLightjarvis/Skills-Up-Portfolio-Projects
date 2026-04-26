<?php

declare(strict_types=1);

namespace Pest\Profanity;




final class Error
{



public function __construct(
public readonly string $file,
public readonly int $line,
public readonly string $word,
) {

}




public function getShortType(): string
{
return 'pr';
}
}
