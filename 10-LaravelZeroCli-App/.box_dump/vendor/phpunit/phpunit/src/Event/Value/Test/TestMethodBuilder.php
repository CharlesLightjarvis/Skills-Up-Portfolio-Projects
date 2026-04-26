<?php declare(strict_types=1);








namespace PHPUnit\Event\Code;

use function is_numeric;
use PHPUnit\Event\TestData\DataFromDataProvider;
use PHPUnit\Event\TestData\DataFromTestDependency;
use PHPUnit\Event\TestData\TestDataCollection;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\Parser\Registry as MetadataRegistry;
use PHPUnit\Util\Exporter;
use PHPUnit\Util\Reflection;
use PHPUnit\Util\Test as TestUtil;

/**
@no-named-arguments


*/
final readonly class TestMethodBuilder
{
public static function fromTestCase(TestCase $testCase, bool $useTestCaseForTestDox = true): TestMethod
{
$methodName = $testCase->name();
$location = Reflection::sourceLocationFor($testCase::class, $methodName);

if ($useTestCaseForTestDox) {
$testDox = TestDoxBuilder::fromTestCase($testCase);
} else {
$testDox = TestDoxBuilder::fromClassNameAndMethodName($testCase::class, $testCase->name());
}

return new TestMethod(
$testCase::class,
$methodName,
$location['file'],
$location['line'],
$testDox,
MetadataRegistry::parser()->forClassAndMethod($testCase::class, $methodName),
self::dataFor($testCase),
);
}




public static function fromCallStack(): TestMethod
{
return TestUtil::currentTestCase()->valueObjectForEvents();
}

private static function dataFor(TestCase $testCase): TestDataCollection
{
$testData = [];

if ($testCase->usesDataProvider()) {
$dataSetName = $testCase->dataName();

if (is_numeric($dataSetName)) {
$dataSetName = (int) $dataSetName;
}

$testData[] = DataFromDataProvider::from(
$dataSetName,
Exporter::shortenedRecursiveExport($testCase->providedData()),
$testCase->dataSetAsStringWithData(),
);
}

if ($testCase->hasDependencyInput()) {
$testData[] = DataFromTestDependency::from(
Exporter::shortenedRecursiveExport($testCase->dependencyInput()),
);
}

return TestDataCollection::fromArray($testData);
}
}
