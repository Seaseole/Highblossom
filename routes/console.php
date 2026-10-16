<?php

use App\Jobs\WarmSystemOverviewProbe;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule temp uploads cleanup daily at 2 AM
Schedule::command('content-blocks:cleanup-orphaned-uploads')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/cleanup-orphaned-uploads.log'));

// Close lapsed sessions and trim device history daily at 3 AM
Schedule::command('sessions:prune')
    ->dailyAt('03:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/prune-user-sessions.log'));

// Warm the heavy system overview sections so an admin never pays for a file
// system walk, whoever opens the page last.
Schedule::job(new WarmSystemOverviewProbe)
    ->twiceDaily(1, 13)
    ->name('system-overview-warm')
    ->withoutOverlapping();
