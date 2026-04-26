<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

use function Illuminate\Filesystem\join_paths;

#[AsCommand(name: 'make:config', aliases: ['config:make'])]
class ConfigMakeCommand extends GeneratorCommand
{





protected $name = 'make:config';






protected $description = 'Create a new configuration file';






protected $type = 'Config';






protected $aliases = ['config:make'];






protected function getPath($name): string
{
return config_path(Str::finish($this->argument('name'), '.php'));
}




protected function getStub(): string
{
$relativePath = join_paths('stubs', 'config.stub');

return file_exists($customPath = $this->laravel->basePath($relativePath))
? $customPath
: join_paths(__DIR__, $relativePath);
}




protected function getOptions(): array
{
return [
['force', 'f', InputOption::VALUE_NONE, 'Create the configuration file even if it already exists'],
];
}






protected function promptForMissingArgumentsUsing()
{
return [
'name' => 'What should the configuration file be named?',
];
}
}
