<?php

namespace Illuminate\Testing;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\Concerns\TestCaches;
use Illuminate\Testing\Concerns\TestDatabases;
use Illuminate\Testing\Concerns\TestViews;

class ParallelTestingServiceProvider extends ServiceProvider implements DeferrableProvider
{
use TestCaches, TestDatabases, TestViews;






public function boot()
{
if ($this->app->runningInConsole()) {
$this->bootTestCache();
$this->bootTestDatabase();
$this->bootTestViews();
}
}






public function register()
{
if ($this->app->runningInConsole()) {
$this->app->singleton(ParallelTesting::class, function () {
return new ParallelTesting($this->app);
});
}
}
}
