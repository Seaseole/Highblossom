<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Jobs\WarmSystemOverviewProbe;
use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeRegistry;
use App\Support\SystemOverview\ProbeRunner;
use App\Support\SystemOverview\SystemOverviewExport;
use Auditify\Facades\Auditify;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Read-only diagnostics dashboard describing the running application.
 */
final class SystemOverviewController
{
    public function __construct(
        private readonly ProbeRunner $probes,
        private readonly ProbeRegistry $registry,
        private readonly SystemOverviewExport $export,
    ) {}

    /**
     * Display the overview: light sections measured inline, heavy ones loaded after.
     *
     * Heavy sections are read here only to report their cached verdict; the card
     * itself is rendered by its own lazy component.
     */
    public function index(): View
    {
        return view('admin.system-overview.index', [
            'sections' => $this->probes->runAll(),
            'heavyProbes' => $this->registry->keysByCost(ProbeCost::Heavy),
        ]);
    }

    /**
     * Drop every cached section, then hand the heavy ones back to the queue.
     */
    public function refresh(): RedirectResponse
    {
        $this->probes->forgetAll();

        WarmSystemOverviewProbe::dispatch();

        return back()->with('success', __('messages.system_overview_refreshed'));
    }

    /**
     * Download the overview as redacted JSON, recording who took it.
     *
     * The audit row is written before the payload leaves, so an export that reaches a
     * client is an export that was logged.
     */
    public function export(): JsonResponse
    {
        $payload = $this->export->payload();

        Auditify::logActivity('Export System Overview', null, null, [
            'sections' => count($payload['sections']),
            'lines' => array_sum(array_map(
                static fn (array $section): int => count($section['items']),
                $payload['sections'],
            )),
            'withheld_lines' => $payload['withheld_lines'],
            'pending_sections' => array_sum(array_map(
                static fn (array $section): int => $section['status'] === 'pending' ? 1 : 0,
                $payload['sections'],
            )),
        ]);

        return response()->json(
            $payload,
            200,
            ['Content-Disposition' => 'attachment; filename="'.$this->export->fileName().'"'],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
        );
    }
}
