<?php

namespace Illuminate\Events;

use Illuminate\Contracts\Queue\Factory as QueueFactoryContract;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{





public function register()
{
$this->app->singleton('events', function ($app) {
return (new Dispatcher($app))->setQueueResolver(function () {
return app(QueueFactoryContract::class);
})->setTransactionManagerResolver(function () {
return app()->bound('db.transactions')
? app('db.transactions')
: null;
});
});
}
}
