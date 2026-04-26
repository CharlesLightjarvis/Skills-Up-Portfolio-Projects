<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $databasePath = config('database.connections.sqlite.database');
        $databaseDirectory = dirname($databasePath);

        if (! File::exists($databaseDirectory)) {
            File::makeDirectory($databaseDirectory, 0755, true);
        }

        if (! File::exists($databasePath)) {
            File::put($databasePath, '');
        }

        if (! Schema::hasTable('products')) {
            Artisan::call('migrate', [
                '--force' => true,
            ]);
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
