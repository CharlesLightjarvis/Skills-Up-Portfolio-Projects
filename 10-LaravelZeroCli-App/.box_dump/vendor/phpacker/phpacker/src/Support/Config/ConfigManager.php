<?php

namespace PHPacker\PHPacker\Support\Config;

use BadMethodCallException;
use Laravel\Prompts\Prompt;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Console\Input\InputInterface;
use PHPacker\PHPacker\Command\Concerns\InteractsWithFiles;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use function Laravel\Prompts\info;







class ConfigManager
{
use InteractsWithFiles;

protected static ConfigRepository $repository;

protected static bool $loaded = false;

const INTERNAL_CONFIG = __DIR__ . '/../../../config/phpacker.json';

const PROXY_METHODS = [
'get',
'set',
'all',
'merge',
];


public static function __callStatic($method, $arguments)
{
if (in_array($method, self::PROXY_METHODS)) {
return self::$repository->$method(...$arguments);
}

throw new BadMethodCallException("Method {$method} does not exist.");
}

public static function getRepository(): ConfigRepository
{
return self::$repository;
}

public static function bootstrap(EventDispatcherInterface $dispatcher)
{

self::$repository = new ConfigRepository(
self::readJsonFile(self::INTERNAL_CONFIG),
);


$dispatcher->addListener('console.command', function ($event) {


if (self::$loaded) {
return;
}



Prompt::setOutput($event->getOutput());

$input = $event->getInput();


self::$repository->merge(
self::configFromCommand($input)
);


self::$repository->merge([
'ini' => self::iniFromCommand($input),
]);


self::$repository->merge(
array_filter($input->getArguments()),
);

self::$loaded = true;
}, priority: 50);
}

public static function reset()
{
self::$loaded = false;
self::$repository = new ConfigRepository(
self::readJsonFile(self::INTERNAL_CONFIG),
);
}
















private static function configFromCommand(InputInterface $input): array
{
$internalConfig = self::readJsonFile(self::INTERNAL_CONFIG);


$configPath = $input->hasOption('config')
? $input->getOption('config')
: false;

if (is_string($configPath)) {
info("Using config file at '{$configPath}'");

$config = array_merge($internalConfig, self::readJsonFile($configPath));

return self::convertPaths($config, dirname($configPath));
}


$sourceFile = $input->hasOption('src')
? $input->getOption('src')
: false;

if (is_string($sourceFile)) {

$sourceDir = dirname($sourceFile);
$configPath = Path::join($sourceDir, 'phpacker.json');


if (file_exists($configPath)) {
info("Using config file at '{$configPath}'");

$config = array_merge($internalConfig, self::readJsonFile($configPath));

return self::convertPaths($config, dirname($configPath));
}



$internalConfig = self::convertPaths($internalConfig, $sourceDir);
}


$configPath = Path::join(getcwd(), 'phpacker.json');

if (file_exists($configPath)) {
info("Using config file at '{$configPath}'");

$config = array_merge($internalConfig, self::readJsonFile($configPath));

return self::convertPaths($config, './');
}

return $internalConfig;
}









private static function iniFromCommand(InputInterface $input): array
{

$iniPath = $input->hasOption('ini')
? $input->getOption('ini')
: false;

if (is_string($iniPath)) {
info("Using ini file at '{$iniPath}'");

return self::readIniFile($iniPath);
}


$iniPath = self::$repository->get('ini');

if (is_string($iniPath) && $iniPath != '') {
info("Using ini file at '{$iniPath}'");

return self::readIniFile($iniPath);
}


$sourceFile = $input->hasOption('src')
? $input->getOption('src')
: false;

if (is_string($sourceFile)) {

$sourceDir = dirname($sourceFile);
$iniPath = Path::join($sourceDir, 'phpacker.ini');


if (file_exists($iniPath)) {
info("Using ini file at '{$iniPath}'");

return self::readIniFile($iniPath);
}
}


$iniPath = Path::join($iniPath, 'phpacker.ini');

if (file_exists($iniPath)) {
info("Using ini file at '{$iniPath}'");

return self::readIniFile($iniPath);
}


return [];
}

private static function convertPaths(array $config, $basePath): array
{
$convert = [
'src',
'dest',
'ini',
];

foreach ($convert as $key) {
if (isset($config[$key]) && is_string($config[$key])) {

$config[$key] = Path::makeAbsolute($config[$key], realpath($basePath));
}
}

return $config;
}
}
