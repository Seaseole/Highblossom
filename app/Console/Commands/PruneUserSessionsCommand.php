<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\UserSessionService;
use Illuminate\Console\Command;

/**
 * Reconciles the session ledger with reality on a schedule.
 *
 * The framework session store expires rows lazily and never reports the ones it
 * dropped, so idle sessions are closed here and history is trimmed to the
 * retention window.
 */
final class PruneUserSessionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:prune {--days= : Override how many days of closed sessions to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close sessions that lapsed and delete session history past the retention window';

    /**
     * Create a new command instance.
     */
    public function __construct(
        protected UserSessionService $sessions
    ) {
        parent::__construct();
    }

    /**
     * Run the reconciliation and report what changed.
     *
     * @return int Command exit code
     */
    public function handle(): int
    {
        $retentionDays = $this->option('days') === null ? null : (int) $this->option('days');

        if ($retentionDays !== null && $retentionDays < 1) {
            $this->error('The --days option must be a positive number of days.');

            return self::FAILURE;
        }

        $result = $this->sessions->prune($retentionDays);

        $this->info("Closed {$result['closed']} lapsed session(s).");
        $this->info("Removed {$result['purged']} session row(s) past the retention window.");

        return self::SUCCESS;
    }
}
