<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\ContactMessageReceived;
use App\Listeners\SendContactMessageEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Event service provider for the application.
 *
 * Registers event-to-listener mappings used throughout the application.
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ContactMessageReceived::class => [
            SendContactMessageEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
