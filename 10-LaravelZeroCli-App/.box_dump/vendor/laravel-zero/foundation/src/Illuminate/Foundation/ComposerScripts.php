<?php

namespace Illuminate\Foundation;

use Composer\Installer\PackageEvent;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Illuminate\Concurrency\ProcessDriver;
use Illuminate\Encryption\EncryptionServiceProvider;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Throwable;

class ComposerScripts
{






public static function postInstall(Event $event)
{
require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

static::clearCompiled();
}







public static function postUpdate(Event $event)
{
require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

static::clearCompiled();
}







public static function postAutoloadDump(Event $event)
{
require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

static::clearCompiled();
}







public static function prePackageUninstall(PackageEvent $event)
{

if (! $event->isDevMode()) {
return;
}

$eventName = null;
try {
require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

$laravel = new Application(getcwd());

$laravel->bootstrapWith([
LoadEnvironmentVariables::class,
LoadConfiguration::class,
]);


(new EncryptionServiceProvider($laravel))->register();

$name = $event->getOperation()->getPackage()->getName();
$eventName = "composer_package.{$name}:pre_uninstall";

$laravel->make(ProcessDriver::class)->run(
static fn () => app()['events']->dispatch($eventName)
);
} catch (Throwable $e) {

$event->getIO()->write('There was an error dispatching or handling the ['.($eventName ?? 'unknown').'] event. Continuing with package removal...');
$event->getIO()->writeError('Exception message: '.$e->getMessage(), verbosity: IOInterface::VERBOSE); 
}
}






protected static function clearCompiled()
{
$laravel = new Application(getcwd());

if (is_file($configPath = $laravel->getCachedConfigPath())) {
@unlink($configPath);
}

if (is_file($servicesPath = $laravel->getCachedServicesPath())) {
@unlink($servicesPath);
}

if (is_file($packagesPath = $laravel->getCachedPackagesPath())) {
@unlink($packagesPath);
}
}
}
