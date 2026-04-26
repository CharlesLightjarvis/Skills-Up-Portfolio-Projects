<?php declare(strict_types=1);








namespace PHPUnit\Event\Code;

use PHPUnit\Framework\TestCase;
use PHPUnit\Logging\TestDox\NamePrettifier;

/**
@no-named-arguments


*/
final class TestDoxBuilder
{
private static ?NamePrettifier $namePrettifier = null;

public static function fromTestCase(TestCase $testCase): TestDox
{
$prettifier = self::namePrettifier();

return new TestDox(
$prettifier->prettifyTestClassName($testCase::class),
$prettifier->prettifyTestCase($testCase, false),
$prettifier->prettifyTestCase($testCase, true),
);
}





public static function fromClassNameAndMethodName(string $className, string $methodName): TestDox
{
$prettifier = self::namePrettifier();

$prettifiedMethodName = $prettifier->prettifyTestMethodName($methodName);

return new TestDox(
$prettifier->prettifyTestClassName($className),
$prettifiedMethodName,
$prettifiedMethodName,
);
}

private static function namePrettifier(): NamePrettifier
{
if (self::$namePrettifier === null) {
self::$namePrettifier = new NamePrettifier;
}

return self::$namePrettifier;
}
}
