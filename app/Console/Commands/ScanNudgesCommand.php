<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\NudgeService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Measure content-hygiene signals and reconcile the nudge store')]
#[Signature('nudges:scan')]
/**
 * Runs the rule-based nudge scanner on a schedule.
 *
 * Every rule is a local query; no provider is called and no draft text leaves
 * the server. With the AI flag off the command is a no-op, so the schedule can
 * stay registered on environments that have not opted in.
 */
final class ScanNudgesCommand extends Command
{
    /**
     * Create a new command instance.
     */
    public function __construct(
        protected NudgeService $nudges
    ) {
        parent::__construct();
    }

    /**
     * Scan every rule and report what changed.
     *
     * @return int Command exit code
     */
    public function handle(): int
    {
        if (! config('ai.enabled')) {
            $this->comment('AI suggestions are disabled; nothing scanned.');

            return self::SUCCESS;
        }

        $counts = $this->nudges->scan();

        $this->info("Nudges: {$counts['created']} created, {$counts['updated']} updated, {$counts['resolved']} resolved.");

        return self::SUCCESS;
    }
}
