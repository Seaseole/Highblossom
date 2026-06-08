<?php

use App\Providers\AppServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\PasswordRulesServiceProvider;
use App\Providers\SeoServiceProvider;
use Laravel\Passkeys\PasskeysServiceProvider;

return [
    FortifyServiceProvider::class,
    PasswordRulesServiceProvider::class,
    SeoServiceProvider::class,
    EventServiceProvider::class,
    PasskeysServiceProvider::class,
    AppServiceProvider::class,
];
