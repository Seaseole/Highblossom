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

// Delete expired session rows and close the sign-ins that lapsed. Hourly rather
// than nightly because a stale row is a wrong number on the overview card for a
// whole day otherwise, and the overlap lock expires after 10 minutes so a run
// killed mid-sweep cannot suppress the next 23 hours of them.
Schedule::command('sessions:prune')
    ->hourly()
    ->withoutOverlapping(10)
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/prune-user-sessions.log'));

// Warm the heavy system overview sections so an admin never pays for a file
// system walk, whoever opens the page last.
Schedule::job(new WarmSystemOverviewProbe)
    ->twiceDaily(1, 13)
    ->name('system-overview-warm')
    ->withoutOverlapping();
