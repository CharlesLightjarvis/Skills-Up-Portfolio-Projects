<?php

namespace App\Actions\Links;

use App\Models\Collection;
use App\Models\Link;
use App\Repositories\Links\LinkRepository;
use Illuminate\Support\Facades\DB;

class CreateLinkAction
{
    public function __construct(private readonly LinkRepository $repository) {}

    public function handle(Collection $collection, array $data): Link
    {
        return DB::transaction(function () use ($collection, $data) {
            return $this->repository->create($collection, $data);
        });
    }
}
