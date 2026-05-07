<?php

namespace App\Providers;

use App\Repositories\Collections\CollectionRepository;
use App\Repositories\Collections\EloquentCollectionRepository;
use App\Repositories\Links\EloquentLinkRepository;
use App\Repositories\Links\LinkRepository;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use ImageKit\ImageKit;
use League\Flysystem\Filesystem;
use TaffoVelikoff\ImageKitAdapter\ImageKitAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CollectionRepository::class, EloquentCollectionRepository::class);
        $this->app->bind(LinkRepository::class, EloquentLinkRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureImageKit();
    }

    protected function configureImageKit(): void
    {
        Storage::extend('imagekit', function ($app, $config) {
            $adapter = new ImageKitAdapter(
                new ImageKit(
                    $config['public_key'],
                    $config['private_key'],
                    $config['endpoint_url'],
                ),
            );

            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config,
            );
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
