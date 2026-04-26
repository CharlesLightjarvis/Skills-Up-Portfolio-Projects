<?php declare(strict_types=1);








namespace PHPUnit\Runner\Phpt;

use const DIRECTORY_SEPARATOR;
use function assert;
use function dirname;
use function explode;
use function file;
use function file_get_contents;
use function is_file;
use function is_readable;
use function is_string;
use function preg_match;
use function realpath;
use function rtrim;
use function str_contains;
use function trim;
use PHPUnit\Runner\Exception;

/**
@no-named-arguments




*/
final readonly class Parser
{







public function parse(string $phptFile): array
{
$sections = [];
$section = '';

$unsupportedSections = [
'CGI',
'COOKIE',
'DEFLATE_POST',
'EXPECTHEADERS',
'EXTENSIONS',
'GET',
'GZIP_POST',
'HEADERS',
'PHPDBG',
'POST',
'POST_RAW',
'PUT',
'REDIRECTTEST',
'REQUEST',
];

$lineNr = 0;

foreach (file($phptFile) as $line) {
$lineNr++;

if (preg_match('/^--([_A-Z]+)--/', $line, $result)) {
$section = $result[1];
$sections[$section] = '';
$sections[$section . '_offset'] = $lineNr;

continue;
}

if ($section === '') {
throw new InvalidPhptFileException;
}

$sections[$section] .= $line;
}

if (isset($sections['FILEEOF'])) {
$sections['FILE'] = rtrim($sections['FILEEOF'], "\r\n");

unset($sections['FILEEOF']);
}

$this->parseExternal($phptFile, $sections);
$this->validate($sections);

foreach ($unsupportedSections as $unsupportedSection) {
if (isset($sections[$unsupportedSection])) {
throw new UnsupportedPhptSectionException($unsupportedSection);
}
}

return $sections;
}




public function parseEnvSection(string $content): array
{
$env = [];

foreach (explode("\n", trim($content)) as $e) {
$e = explode('=', trim($e), 2);

if ($e[0] !== '' && isset($e[1])) {
$env[$e[0]] = $e[1];
}
}

return $env;
}







public function parseIniSection(array|string $content, array $ini = []): array
{
if (is_string($content)) {
$content = explode("\n", trim($content));
}

foreach ($content as $setting) {
if (!str_contains($setting, '=')) {
continue;
}

$setting = explode('=', $setting, 2);
$name = trim($setting[0]);
$value = trim($setting[1]);

if ($name === 'extension' || $name === 'zend_extension') {
if (!isset($ini[$name])) {
$ini[$name] = [];
}

$ini[$name][] = $value;

continue;
}

$ini[$name] = $value;
}

return $ini;
}







private function parseExternal(string $phptFile, array &$sections): void
{
$allowSections = [
'FILE',
'EXPECT',
'EXPECTF',
'EXPECTREGEX',
];

$testDirectory = dirname($phptFile) . DIRECTORY_SEPARATOR;

foreach ($allowSections as $section) {
if (isset($sections[$section . '_EXTERNAL'])) {
$externalFilename = trim($sections[$section . '_EXTERNAL']);

if (!is_file($testDirectory . $externalFilename) ||
!is_readable($testDirectory . $externalFilename)) {
throw new PhptExternalFileCannotBeLoadedException(
$section,
$testDirectory . $externalFilename,
);
}

$externalPath = $testDirectory . $externalFilename;
$contents = file_get_contents($externalPath);

assert($contents !== false && $contents !== '');

$sections[$section] = $contents;

if ($section === 'FILE') {
$resolvedPath = realpath($externalPath);

assert(is_string($resolvedPath) && $resolvedPath !== '');

$sections['FILE_EXTERNAL_PATH'] = $resolvedPath;
}
}
}
}






private function validate(array $sections): void
{
if (!isset($sections['FILE'])) {
throw new InvalidPhptFileException;
}

if (!isset($sections['EXPECT']) &&
!isset($sections['EXPECTF']) &&
!isset($sections['EXPECTREGEX'])) {
throw new InvalidPhptFileException;
}
}
}
