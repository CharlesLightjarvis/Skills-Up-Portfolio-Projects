<?php

namespace App\Actions\Links;

use App\Models\Link;
use App\Repositories\Links\LinkRepository;
use Illuminate\Support\Facades\DB;

class UpdateLinkAction
{
    public function __construct(private readonly LinkRepository $repository) {}

    public function handle(Link $link, array $data): Link
    {
        return DB::transaction(function () use ($link, $data) {
            return $this->repository->update($link, $data);
        });
    }
}
