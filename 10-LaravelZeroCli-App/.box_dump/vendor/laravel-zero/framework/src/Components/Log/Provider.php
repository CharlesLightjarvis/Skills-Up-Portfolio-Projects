<?php

declare(strict_types=1);










namespace LaravelZero\Framework\Components\Log;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Log\ContextLogProcessor as ContextLogProcessorContract;
use Illuminate\Log\Context\ContextLogProcessor;
use Illuminate\Log\LogServiceProvider;
use LaravelZero\Framework\Components\AbstractComponentProvider;

use function class_exists;




final class Provider extends AbstractComponentProvider
{



public function isAvailable(): bool
{
return file_exists($this->app->configPath('logging.php'))
&& $this->app['config']->get('logging.useDefaultProvider', true) === true;
}




public function register(): void
{
$this->app->register(LogServiceProvider::class);
if (class_exists(ContextLogProcessor::class)) {
$this->app->bind(ContextLogProcessorContract::class, fn () => new ContextLogProcessor);
}


$config = $this->app['config'];

$config->set('logging.default', $config->get('logging.default') ?: 'default');
}
}
