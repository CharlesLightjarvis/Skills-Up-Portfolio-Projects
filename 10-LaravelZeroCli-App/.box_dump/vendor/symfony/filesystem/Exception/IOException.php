<?php










namespace Symfony\Component\Filesystem\Exception;








class IOException extends \RuntimeException implements IOExceptionInterface
{
public function __construct(
string $message,
int $code = 0,
?\Throwable $previous = null,
private ?string $path = null,
) {
parent::__construct($message, $code, $previous);
}

public function getPath(): ?string
{
return $this->path;
}
}
