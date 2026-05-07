<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(2)->create()->each(function (User $user) {
            Collection::factory(10)->create(['user_id' => $user->id])->each(function (Collection $collection) {
                Link::factory(10)->create(['collection_id' => $collection->id]);
            });
        });
    }
}
