<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Util;

use function dirname;
use function file_put_contents;
use function is_dir;
use function mkdir;
use function sprintf;
use function str_contains;
use SebastianBergmann\CodeCoverage\WriteOperationFailedException;




final class Filesystem
{



public static function createDirectory(string $directory): void
{
$success = !(!is_dir($directory) && !@mkdir($directory, 0o777, true) && !is_dir($directory));

if (!$success) {
throw new DirectoryCouldNotBeCreatedException(
sprintf(
'Directory "%s" could not be created',
$directory,
),
);
}
}






public static function write(string $target, string $buffer): void
{
if (!str_contains($target, '://')) {
self::createDirectory(dirname($target));
}

if (@file_put_contents($target, $buffer) === false) {
throw new WriteOperationFailedException($target);
}
}
}
