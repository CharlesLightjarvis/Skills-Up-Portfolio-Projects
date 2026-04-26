<?php

namespace App\Commands;

use App\Actions\ListProductsAction;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use function Termwind\render;

class ListProductsCommand extends Command
{
    protected $signature = 'product:list';

    protected $description = 'List all products';

    public function handle(ListProductsAction $listProductsAction): int
    {
        $products = $listProductsAction->execute();

        if ($products->isEmpty()) {
            render(<<<'HTML'
                <div class="py-1 ml-2">
                    <div class="px-1 bg-yellow-300 text-black">No products found.</div>
                </div>
            HTML);

            return self::SUCCESS;
        }

        $items = $products
            ->map(fn ($product) => '<div class="ml-1">- '.'['.e($product->id).'] - '.e($product->name).'</div>')
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

        $this->notify('Hello Web Artisan', 'Love beautiful..');

        return self::SUCCESS;
    }

    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
