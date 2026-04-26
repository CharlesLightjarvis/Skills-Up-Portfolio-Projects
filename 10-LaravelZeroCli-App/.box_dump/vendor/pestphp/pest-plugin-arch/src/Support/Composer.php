<?php

declare(strict_types=1);

namespace Pest\Arch\Support;

use Composer\Autoload\ClassLoader;
use Pest\TestSuite;




final class Composer
{





public static function userNamespaces(): array
{
return array_values(self::userNamespacesWithDirectories());
}






public static function userNamespacesWithDirectories(): array
{
$rootPath = TestSuite::getInstance()->rootPath.DIRECTORY_SEPARATOR;

return array_filter(
self::allNamespacesWithDirectories(),
static fn (string $namespace, string $directory): bool => ! str_starts_with($directory, $rootPath.'tests') || str_ends_with($directory, 'pest-plugin-arch'.DIRECTORY_SEPARATOR.'tests'),
ARRAY_FILTER_USE_BOTH,
);
}






public static function allNamespacesWithDirectories(): array
{
$namespaces = [];

$rootPath = TestSuite::getInstance()->rootPath.DIRECTORY_SEPARATOR;

foreach (self::loader()->getPrefixesPsr4() as $namespace => $directories) {
foreach ($directories as $directory) {
$directory = realpath($directory);

if ($directory === false) {
continue;
}

if (str_starts_with($directory, $rootPath.'vendor')) {
continue;
}

$namespaces[$directory] = rtrim($namespace, '\\');
}
}

return $namespaces;
}




public static function loader(): ClassLoader
{
$autoload = TestSuite::getInstance()->rootPath.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
$autoloadLines = explode("\n", (string) file_get_contents($autoload));


$loader = eval($autoloadLines[count($autoloadLines) - 2]);

return $loader;
}
}
