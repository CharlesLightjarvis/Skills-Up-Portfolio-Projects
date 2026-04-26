<?php










namespace Symfony\Component\Console\Exception;




class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{



public static function fromEnumValue(string $name, string $value, array|\Closure $suggestedValues): self
{
$error = \sprintf('The value "%s" is not valid for the "%s" argument.', $value, $name);

if (\is_array($suggestedValues)) {
$error .= \sprintf(' Supported values are "%s".', implode('", "', $suggestedValues));
}

return new self($error);
}
}
