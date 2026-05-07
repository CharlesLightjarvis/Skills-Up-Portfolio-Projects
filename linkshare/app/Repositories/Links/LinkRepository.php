<?php

namespace App\Repositories\Links;

use App\Models\Collection;
use App\Models\Link;
use Illuminate\Pagination\LengthAwarePaginator;

interface LinkRepository
{
    public function paginate(Collection $collection, int $perPage = 20): LengthAwarePaginator;

    public function create(Collection $collection, array $data): Link;

    public function update(Link $link, array $data): Link;

    public function delete(Link $link): void;
}
