<?php

namespace App\Repositories\Collections;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentCollectionRepository implements CollectionRepository
{
    public function paginate(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->collections()->latest()->paginate($perPage);
    }

    public function find(int $id): Collection
    {
        return Collection::with('links')->findOrFail($id);
    }

    public function create(array $data): Collection
    {
        return Collection::create($data);
    }

    public function update(Collection $collection, array $data): Collection
    {
        $collection->update($data);

        return $collection->fresh();
    }

    public function delete(Collection $collection): void
    {
        $collection->delete();
    }
}
