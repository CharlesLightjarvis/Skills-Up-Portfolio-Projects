<?php

use App\Providers\AppServiceProvider;

return [
    'name' => 'Application',
    'version' => 'unreleased',
    'env' => 'development',

    'providers' => [
        AppServiceProvider::class,
    ],
];
