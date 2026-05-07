<?php

namespace Database\Factories;

use App\Models\Collection;
use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Link>
 */
class LinkFactory extends Factory
{
    public function definition(): array
    {
        $domain = fake()->domainName();

        return [
            'collection_id' => Collection::factory(),
            'url' => 'https://'.$domain.'/'.fake()->slug(),
            'title' => fake()->sentence(fake()->numberBetween(3, 8)),
            'description' => fake()->optional()->paragraph(),
            'image_url' => fake()->optional()->imageUrl(),
            'site_name' => fake()->optional()->company(),
            'domain' => $domain,
            'note' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['active', 'archived']),
            'is_favorite' => fake()->boolean(20),
        ];
    }
}
