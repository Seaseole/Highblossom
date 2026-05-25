<?php

use App\Providers\AppServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\SeoServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    SeoServiceProvider::class,
    EventServiceProvider::class,
];
