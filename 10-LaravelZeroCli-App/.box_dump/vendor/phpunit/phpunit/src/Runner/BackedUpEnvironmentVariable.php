<?php declare(strict_types=1);








namespace PHPUnit\Runner;

use function getenv;
use function putenv;

/**
@no-named-arguments


*/
final readonly class BackedUpEnvironmentVariable
{
private const string FROM_GETENV = 'getenv';
private const string FROM_SUPERGLOBAL = 'superglobal';




private string $from;




private string $name;
private null|string $value;






public static function create(string $name): array
{
$getenv = getenv($name);

if ($getenv === false) {
$getenv = null;
}

return [
new self(self::FROM_SUPERGLOBAL, $name, $_ENV[$name] ?? null),
new self(self::FROM_GETENV, $name, $getenv),
];
}





private function __construct(string $from, string $name, null|string $value)
{
$this->from = $from;
$this->name = $name;
$this->value = $value;
}

public function restore(): void
{
if ($this->from === self::FROM_GETENV) {
$this->restoreGetEnv();
} else {
$this->restoreSuperGlobal();
}
}

private function restoreGetEnv(): void
{
if ($this->value === null) {
putenv($this->name);
} else {
putenv("{$this->name}={$this->value}");
}
}

private function restoreSuperGlobal(): void
{
if ($this->value === null) {
unset($_ENV[$this->name]);
} else {
$_ENV[$this->name] = $this->value;
}
}
}
