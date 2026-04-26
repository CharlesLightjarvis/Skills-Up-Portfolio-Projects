<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'reload')]
class ReloadCommand extends Command
{





protected $name = 'reload';






protected $description = 'Reload running services';






public function handle()
{
$this->components->info('Reloading services.');

$exceptions = Collection::wrap(explode(',', $this->option('except') ?? ''))
->map(fn ($except) => trim($except))
->filter()
->unique()
->flip();

$tasks = Collection::wrap($this->getReloadTasks())
->reject(fn ($command, $key) => $exceptions->hasAny([$command, $key]))
->toArray();

foreach ($tasks as $description => $command) {
$this->components->task($description, fn () => $this->callSilently($command) == 0);
}

$this->newLine();
}






public function getReloadTasks()
{
return [
'queue' => 'queue:restart',
'schedule' => 'schedule:interrupt',
...ServiceProvider::$reloadCommands,
];
}






protected function getOptions()
{
return [
['except', 'e', InputOption::VALUE_OPTIONAL, 'The commands to skip'],
];
}
}
