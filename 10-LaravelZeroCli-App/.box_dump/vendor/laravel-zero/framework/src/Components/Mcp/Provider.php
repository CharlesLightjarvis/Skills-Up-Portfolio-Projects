<?php

declare(strict_types=1);










namespace LaravelZero\Framework\Components\Mcp;

use Laravel\Mcp\Console\Commands\InspectorCommand;
use Laravel\Mcp\Console\Commands\MakePromptCommand;
use Laravel\Mcp\Console\Commands\MakeResourceCommand;
use Laravel\Mcp\Console\Commands\MakeServerCommand;
use Laravel\Mcp\Console\Commands\MakeToolCommand;
use Laravel\Mcp\Console\Commands\StartCommand;
use Laravel\Mcp\Server\McpServiceProvider;
use LaravelZero\Framework\Components\AbstractComponentProvider;

use function class_exists;




final class Provider extends AbstractComponentProvider
{



public function isAvailable(): bool
{
return class_exists(McpServiceProvider::class);
}

public function boot(): void
{
if ($this->app->environment() !== 'production') {
$this->commands(
[
InspectorCommand::class,
]
);
}

$this->commands(
[
MakePromptCommand::class,
MakeResourceCommand::class,
MakeServerCommand::class,
MakeToolCommand::class,
StartCommand::class,
]
);

if (file_exists($this->app->bootstrapPath('ai.php'))) {
require $this->app->bootstrapPath('ai.php');
}
}




public function register(): void
{
$this->app->register(McpServiceProvider::class);
}
}
