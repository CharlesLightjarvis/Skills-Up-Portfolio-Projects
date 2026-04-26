<?php

namespace Illuminate\Database\Events;

use Illuminate\Database\Connection;

class MigrationsPruned
{





public $connection;






public $connectionName;






public $path;







public function __construct(Connection $connection, string $path)
{
$this->connection = $connection;
$this->connectionName = $connection->getName();
$this->path = $path;
}
}
