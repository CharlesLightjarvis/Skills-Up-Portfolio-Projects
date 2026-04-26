<?php

namespace Illuminate\Database\Console;

use Illuminate\Console\Command;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;

abstract class DatabaseInspectionCommand extends Command
{









protected function getConnectionName(ConnectionInterface $connection, $database)
{
return $connection->getDriverTitle();
}









protected function getConnectionCount(ConnectionInterface $connection)
{
return $connection->threadCount();
}







protected function getConfigFromDatabase($database)
{
$database ??= config('database.default');

return Arr::except(config('database.connections.'.$database), ['password']);
}
}
