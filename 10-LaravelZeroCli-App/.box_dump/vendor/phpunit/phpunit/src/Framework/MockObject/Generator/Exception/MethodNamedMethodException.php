<?php declare(strict_types=1);








namespace PHPUnit\Framework\MockObject\Generator;




final class MethodNamedMethodException extends \PHPUnit\Framework\Exception implements Exception
{
public function __construct()
{
parent::__construct('Doubling interfaces (or classes) that have a method named "method" is not supported.');
}
}
