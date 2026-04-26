<?php declare(strict_types=1);








namespace PHPUnit\Framework;

use function sprintf;

/**
@no-named-arguments


*/
final class UnknownNativeTypeException extends InvalidArgumentException
{
public function __construct(string $type)
{
parent::__construct(
sprintf(
'Native type "%s" is not known',
$type,
),
);
}
}
