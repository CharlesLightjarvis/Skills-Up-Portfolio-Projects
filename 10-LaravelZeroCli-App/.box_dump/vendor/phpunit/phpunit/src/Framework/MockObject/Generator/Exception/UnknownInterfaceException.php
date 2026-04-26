<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject\Generator;

use function sprintf;

/**
@no-named-arguments


*/
final class UnknownInterfaceException extends \PHPUnit\Framework\Exception implements Exception
{
public function __construct(string $interfaceName)
{
parent::__construct(
sprintf(
'Interface "%s" does not exist',
$interfaceName,
),
);
}
}
