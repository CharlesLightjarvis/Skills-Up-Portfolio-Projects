<?php declare(strict_types=1);








namespace PHPUnit\Framework;

/**
@no-named-arguments


*/
final class ErrorLogNotWritableException extends Exception
{
public function __construct()
{
parent::__construct('Could not create writable file for error_log()');
}
}
