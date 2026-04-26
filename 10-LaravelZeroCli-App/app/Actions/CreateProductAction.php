<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class CreateProductAction
{
    public function execute(string $name, ?string $description, float $price): bool
    {
        return DB::transaction(function () use ($name, $description, $price): bool {
            return DB::table('products')->insert([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
