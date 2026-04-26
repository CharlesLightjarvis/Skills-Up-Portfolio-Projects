<?php declare(strict_types=1);








namespace PHPUnit\Framework\Constraint;

use function preg_last_error_msg;
use function preg_match;
use function sprintf;
use PHPUnit\Framework\Exception as FrameworkException;

/**
@no-named-arguments
*/
final class RegularExpression extends Constraint
{
private readonly string $pattern;

public function __construct(string $pattern)
{
$this->pattern = $pattern;
}




public function toString(): string
{
return sprintf(
'matches PCRE pattern "%s"',
$this->pattern,
);
}







protected function matches(mixed $other): bool
{
$matches = @preg_match($this->pattern, $other);

if ($matches === false) {
throw new FrameworkException(
sprintf(
'Regular expression cannot be matched: %s',
preg_last_error_msg(),
),
);
}

return $matches > 0;
}
}
