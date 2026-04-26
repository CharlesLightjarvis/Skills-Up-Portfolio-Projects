<?php

declare(strict_types=1);










namespace LaravelZero\Framework\Components\Menu;

use LaravelZero\Framework\Components\AbstractComponentProvider;
use NunoMaduro\LaravelConsoleMenu\LaravelConsoleMenuServiceProvider;

use function class_exists;




final class Provider extends AbstractComponentProvider
{



public function isAvailable(): bool
{
return class_exists(LaravelConsoleMenuServiceProvider::class);
}




public function register(): void
{
$this->app->register(LaravelConsoleMenuServiceProvider::class);
}
}
