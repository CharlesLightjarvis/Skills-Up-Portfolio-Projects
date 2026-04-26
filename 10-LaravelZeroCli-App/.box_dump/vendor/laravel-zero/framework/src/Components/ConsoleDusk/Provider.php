<?php

declare(strict_types=1);










namespace LaravelZero\Framework\Components\ConsoleDusk;

use LaravelZero\Framework\Components\AbstractComponentProvider;
use NunoMaduro\LaravelConsoleDusk\LaravelConsoleDuskServiceProvider;

use function class_exists;




final class Provider extends AbstractComponentProvider
{



public function isAvailable(): bool
{
return class_exists(LaravelConsoleDuskServiceProvider::class);
}




public function register(): void
{
$this->app->register(LaravelConsoleDuskServiceProvider::class);
}
}
