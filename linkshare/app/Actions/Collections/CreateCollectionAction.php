<?php

namespace App\Actions\Collections;

use App\Models\Collection;
use App\Models\User;
use App\Repositories\Collections\CollectionRepository;
use App\Repositories\Links\LinkRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateCollectionAction
{
    public function __construct(
        private readonly CollectionRepository $collectionRepository,
        private readonly LinkRepository $linkRepository,
    ) {}

    public function handle(User $user, array $data): Collection
    {
        return DB::transaction(function () use ($user, $data) {
            $links = $data['links'] ?? [];
            unset($data['links']);

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $data['image_path'] = $data['image']->store('collections', 'imagekit');
            }
            unset($data['image']);

            $collection = $this->collectionRepository->create([
                ...$data,
                'user_id' => $user->id,
                'slug' => Str::slug($data['name']),
            ]);

            foreach ($links as $linkData) {
                $this->linkRepository->create($collection, $linkData);
            }

            return $collection;
        });
    }
}
