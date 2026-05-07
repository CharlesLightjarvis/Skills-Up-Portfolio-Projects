<?php

namespace Database\Factories;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Collection>
 */
class CollectionFactory extends Factory
{
    public function definition(): array
    {
        $name = ucwords(fake()->words(fake()->numberBetween(1, 3), true));

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->optional()->sentence(),
            'color' => fake()->optional()->safeHexColor(),
        ];
    }
}
