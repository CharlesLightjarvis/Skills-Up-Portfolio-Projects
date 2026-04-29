<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'user_id' => User::factory(),
            'description' => $this->faker->paragraph(1, true),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'image' => 'https://picsum.photos/seed/'.$this->faker->uuid().'/640/480',
            'status' => $this->faker->randomElement(['active', 'inactive'])
        ];
    }
}
