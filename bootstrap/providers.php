<?php

use App\Providers\AppServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\PasswordRulesServiceProvider;
use App\Providers\SeoServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    PasswordRulesServiceProvider::class,
    SeoServiceProvider::class,
    EventServiceProvider::class,
];
