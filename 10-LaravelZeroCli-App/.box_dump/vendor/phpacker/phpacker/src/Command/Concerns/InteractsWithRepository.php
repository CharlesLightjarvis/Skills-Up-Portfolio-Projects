<?php

namespace PHPacker\PHPacker\Command\Concerns;

use PHPacker\PHPacker\Support\GitHub;
use PHPacker\PHPacker\Contracts\RemoteRepositoryService;

trait InteractsWithRepository
{




protected function repository(): RemoteRepositoryService
{
return once(function () {
return new GitHub($this->repository);
});
}
}
