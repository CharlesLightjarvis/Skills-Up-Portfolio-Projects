<?php










namespace Symfony\Component\Translation;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class StaticMessage implements TranslatableInterface
{
public function __construct(
private string $message,
) {
}

public function getMessage(): string
{
return $this->message;
}

public function trans(TranslatorInterface $translator, ?string $locale = null): string
{
return $this->getMessage();
}
}
