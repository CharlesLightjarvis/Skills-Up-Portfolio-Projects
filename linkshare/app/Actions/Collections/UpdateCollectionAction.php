<?php

namespace App\Actions\Collections;

use App\Models\Collection;
use App\Repositories\Collections\CollectionRepository;
use App\Repositories\Links\LinkRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateCollectionAction
{
    public function __construct(
        private readonly CollectionRepository $collectionRepository,
        private readonly LinkRepository $linkRepository,
    ) {}

    public function handle(Collection $collection, array $data): Collection
    {
        return DB::transaction(function () use ($collection, $data) {
            $links = $data['links'] ?? [];
            unset($data['links']);

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                if ($collection->image_path) {
                    Storage::disk('imagekit')->delete($collection->image_path);
                }
                $data['image_path'] = $data['image']->store('collections', 'imagekit');
            }
            unset($data['image']);

            if (isset($data['name'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            $updated = $this->collectionRepository->update($collection, $data);

            foreach ($links as $linkData) {
                $this->linkRepository->create($collection, $linkData);
            }

            return $updated;
        });
    }
}
