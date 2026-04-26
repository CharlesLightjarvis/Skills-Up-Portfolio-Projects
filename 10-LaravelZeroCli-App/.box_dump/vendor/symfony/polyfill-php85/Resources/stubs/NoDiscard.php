<?php










if (\PHP_VERSION_ID < 80500) {
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
final class NoDiscard
{
public ?string $message;

public function __construct(?string $message = null)
{
$this->message = $message;
}
}
}
