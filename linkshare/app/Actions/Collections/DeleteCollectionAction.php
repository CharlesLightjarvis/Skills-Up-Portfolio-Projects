<?php

namespace App\Actions\Collections;

use App\Models\Collection;
use App\Repositories\Collections\CollectionRepository;
use Illuminate\Support\Facades\DB;

class DeleteCollectionAction
{
    public function __construct(private readonly CollectionRepository $repository) {}

    public function handle(Collection $collection): void
    {
        DB::transaction(function () use ($collection) {
            $this->repository->delete($collection);
        });
    }
}
