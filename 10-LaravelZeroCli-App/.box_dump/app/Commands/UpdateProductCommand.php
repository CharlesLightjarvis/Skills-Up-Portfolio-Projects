<?php

namespace App\Commands;

use App\Actions\FindProductByIdAction;
use App\Actions\ListProductsAction;
use App\Actions\UpdateProductAction;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use function Termwind\render;

class UpdateProductCommand extends Command
{
protected $signature = 'product:update';

protected $description = 'Update a product';

public function handle(
ListProductsAction $listProductsAction,
FindProductByIdAction $findProductByIdAction,
UpdateProductAction $updateProductAction
): int {
$products = $listProductsAction->execute();

if ($products->isEmpty()) {
render(<<<'HTML'
                <div class="py-1 ml-2">
                    <div class="px-1 bg-yellow-300 text-black">
                        No products available.
                    </div>
                </div>
            HTML);

return self::SUCCESS;
}

$items = $products
->map(fn ($product) => '<div class="ml-1">- '.'['.e($product->id).'] - '.e($product->name).' '.e($product->price).'$</div>')
->join('');

render(<<<HTML
            <div class="py-1 ml-2">
                <div class="px-1 bg-blue-300 text-black">
                    Here is the list of your products:
                </div>

                <div class="mt-1">
                    {$items}
                </div>
            </div>
        HTML);

$id = $this->ask('Which product ID do you want to update?');

while (! is_numeric($id) || (int) $id <= 0) {
$this->error('You must provide a valid product ID.');
$id = $this->ask('Which product ID do you want to update?');
}

$product = $findProductByIdAction->execute((int) $id);

while (! $product) {
$this->error('Product not found. please provide a valid product ID.');
$id = $this->ask('Which product ID do you want to update?');
}

$name = $this->ask('What is the new product name?', $product->name);

while (! $name) {
$this->error('The product name is required.');
$name = $this->ask('What is the new product name?', $product->name);
}

$description = $this->ask(
'What is the new product description?',
$product->description ?? ''
);

$description = $description ?: null;

$price = $this->ask('What is the new product price?', $product->price);

while (! is_numeric($price) || (float) $price < 0) {
$this->error('The product price must be a valid positive number.');
$price = $this->ask('What is the new product price?', $product->price);
}

$updated = $updateProductAction->execute(
product: $product,
name: $name,
description: $description,
price: (float) $price,
);

if ($updated === 0) {
render(<<<'HTML'
                <div class="py-1 ml-2">
                    <div class="px-1 bg-yellow-300 text-black">
                        No changes detected.
                    </div>
                </div>
            HTML);

return self::SUCCESS;
}

$descriptionText = $description ?: 'No description';

render(<<<HTML
            <div class="py-1 ml-2">
                <div class="px-1 bg-green-300 text-black">
                    Product updated successfully.
                </div>

                <div class="mt-1 ml-1">
                    <div>Name: {$name}</div>
                    <div>Price: {$price}</div>
                    <div>Description: {$descriptionText}</div>
                </div>
            </div>
        HTML);

return self::SUCCESS;
}

public function schedule(Schedule $schedule): void
{

}
}
