<?php

namespace Illuminate\Cache\Console;

use Illuminate\Cache\CacheManager;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'cache:prune-stale-tags')]
class PruneStaleTagsCommand extends Command
{





protected $name = 'cache:prune-stale-tags';






protected $description = 'Prune stale cache tags from the cache (Redis only)';







public function handle(CacheManager $cache)
{
$cache = $cache->store($this->argument('store'));

if (method_exists($cache->getStore(), 'flushStaleTags')) {
$cache->flushStaleTags();
}

$this->components->info('Stale cache tags pruned successfully.');
}






protected function getArguments()
{
return [
['store', InputArgument::OPTIONAL, 'The name of the store you would like to prune tags from'],
];
}
}
