<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class UpdateProductAction
{
public function execute(object $product, string $name, ?string $description, float $price): int
{
return DB::transaction(function () use ($product, $name, $description, $price): int {
return DB::table('products')
->where('id', $product->id)
->update([
'name' => $name,
'description' => $description,
'price' => $price,
'updated_at' => now(),
]);
});
}
}
