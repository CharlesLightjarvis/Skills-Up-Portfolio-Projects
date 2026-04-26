<?php declare(strict_types=1);








namespace PHPUnit\Framework;

use Throwable;

/**
@no-named-arguments


*/
final class InvalidDataProviderException extends Exception
{
private ?string $providerLabel = null;

public static function forException(Throwable $e, string $providerLabel): self
{
$exception = new self(
$e->getMessage(),
$e->getCode(),
$e,
);
$exception->providerLabel = $providerLabel;

return $exception;
}

public function getProviderLabel(): ?string
{
return $this->providerLabel;
}
}
