<?php

namespace App\Repositories\Collections;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface CollectionRepository
{
    public function paginate(User $user, int $perPage = 10): LengthAwarePaginator;

    public function find(int $id): Collection;

    public function create(array $data): Collection;

    public function update(Collection $collection, array $data): Collection;

    public function delete(Collection $collection): void;
}
