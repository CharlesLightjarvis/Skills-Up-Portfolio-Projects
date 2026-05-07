<?php

namespace App\Actions\Links;

use App\Models\Link;
use App\Repositories\Links\LinkRepository;
use Illuminate\Support\Facades\DB;

class DeleteLinkAction
{
    public function __construct(private readonly LinkRepository $repository) {}

    public function handle(Link $link): void
    {
        DB::transaction(function () use ($link) {
            $this->repository->delete($link);
        });
    }
}
