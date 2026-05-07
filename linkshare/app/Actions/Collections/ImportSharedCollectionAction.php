<?php

namespace App\Actions\Collections;

use App\Models\Collection;
use App\Models\Share;
use App\Models\User;
use App\Repositories\Collections\CollectionRepository;
use App\Repositories\Links\LinkRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportSharedCollectionAction
{
    public function __construct(
        private CollectionRepository $collectionRepository,
        private LinkRepository $linkRepository,
    ) {}

    public function handle(User $user, Share $share): Collection
    {
        /** @var Collection $source */
        $source = $share->shareable;

        return DB::transaction(function () use ($user, $source) {
            $imagePath = null;
            if ($source->image_path && Storage::disk('imagekit')->exists($source->image_path)) {
                $extension = pathinfo($source->image_path, PATHINFO_EXTENSION);
                $imagePath = 'collections/'.Str::uuid().'.'.$extension;
                Storage::disk('imagekit')->copy($source->image_path, $imagePath);
            }

            $collection = $this->collectionRepository->create([
                'user_id' => $user->id,
                'name' => $source->name,
                'slug' => Str::slug($source->name).'-'.Str::lower(Str::random(4)),
                'description' => $source->description,
                'color' => $source->color,
                'image_path' => $imagePath,
            ]);

            foreach ($source->links as $link) {
                $this->linkRepository->create($collection, [
                    'url' => $link->url,
                    'title' => $link->title,
                    'description' => $link->description,
                    'image_url' => $link->image_url,
                    'site_name' => $link->site_name,
                    'domain' => $link->domain,
                ]);
            }

            return $collection;
        });
    }
}
