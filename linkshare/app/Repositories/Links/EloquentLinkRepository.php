<?php

namespace App\Repositories\Links;

use App\Models\Collection;
use App\Models\Link;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentLinkRepository implements LinkRepository
{
    public function paginate(Collection $collection, int $perPage = 20): LengthAwarePaginator
    {
        return $collection->links()->latest()->paginate($perPage);
    }

    public function create(Collection $collection, array $data): Link
    {
        return $collection->links()->create($data);
    }

    public function update(Link $link, array $data): Link
    {
        $link->update($data);

        return $link->fresh();
    }

    public function delete(Link $link): void
    {
        $link->delete();
    }
}
