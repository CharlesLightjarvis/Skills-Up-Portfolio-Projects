<?php

declare(strict_types=1);










namespace LaravelZero\Framework\Components\View;

use Illuminate\View\ViewServiceProvider;
use LaravelZero\Framework\Components\AbstractComponentProvider;

use function class_exists;




final class Provider extends AbstractComponentProvider
{



public function isAvailable(): bool
{
return class_exists(ViewServiceProvider::class);
}




public function register(): void
{
$this->app->register(ViewServiceProvider::class);
}
}
