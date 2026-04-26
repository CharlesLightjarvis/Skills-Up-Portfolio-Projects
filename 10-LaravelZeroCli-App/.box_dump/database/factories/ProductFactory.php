<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
@extends
*/
class ProductFactory extends Factory
{





public function definition(): array
{
return [
'name' => $this->faker->name(),
'description' => $this->faker->paragraph(),
'price' => $this->faker->randomFloat(2, 10, 100),
];
}
}
