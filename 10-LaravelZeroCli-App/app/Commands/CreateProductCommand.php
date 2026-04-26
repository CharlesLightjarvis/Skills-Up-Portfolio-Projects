<?php

namespace App\Commands;

use App\Actions\CreateProductAction;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Throwable;

use function Termwind\render;

class CreateProductCommand extends Command
{
    protected $signature = 'product:create';

    protected $description = 'Create a product';

    public function handle(CreateProductAction $createProductAction): int
    {
        $name = $this->ask('What is the name of your product?');

        while (! $name) {
            $this->error('The product name is required.');
            $name = $this->ask('What is the name of your product?');
        }

        $price = $this->ask('What is the price of your product?');

        while (! is_numeric($price) || (float) $price < 0) {
            $this->error('The price must be a valid positive number.');
            $price = $this->ask('What is the price of your product?');
        }

        $description = null;

        if ($this->confirm('Do you want to add a description?', false)) {
            $description = $this->ask('What is the description of your product?');
        }

        $description = $description ?: null;
        $descriptionText = $description ?: 'No description';

        try {
            $created = $createProductAction->execute(
                name: $name,
                description: $description,
                price: (float) $price,
            );
        } catch (Throwable $e) {
            render(<<<HTML
                <div class="py-1 ml-2">
                    <div class="px-1 bg-red-300 text-black">
                        Product creation failed.
                    </div>

                    <div class="mt-1 ml-1">
                        {$e->getMessage()}
                    </div>
                </div>
            HTML);

            return self::FAILURE;
        }

        if (! $created) {
            render(<<<'HTML'
                <div class="py-1 ml-2">
                    <div class="px-1 bg-red-300 text-black">
                        Product creation failed.
                    </div>
                </div>
            HTML);

            return self::FAILURE;
        }

        render(<<<HTML
            <div class="py-1 ml-2">
                <div class="px-1 bg-green-300 text-black">
                    Product created successfully.
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
        // $schedule->command(static::class)->everyMinute();
    }
}
