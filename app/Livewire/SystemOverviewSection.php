<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Jobs\WarmSystemOverviewProbe;
use App\Support\SystemOverview\ProbeRegistry;
use App\Support\SystemOverview\ProbeResult;
use App\Support\SystemOverview\ProbeRunner;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

/**
 * One heavy system overview section, loaded after the page shell.
 *
 * Heavy sections are never measured while rendering a page: the card shows the
 * cached measurement, or asks for one. Two ways to get it, because this project
 * runs with a queue worker in production and without one in development.
 */
final class SystemOverviewSection extends Component
{
    #[Locked]
    public string $probe = '';

    /**
     * @var bool Whether a background warm-up is waiting for a worker.
     */
    public bool $warming = false;

    /**
     * @param string $probe Allowlisted heavy probe key.
     */
    public function mount(string $probe): void
    {
        abort_unless(app(ProbeRegistry::class)->has($probe), 404);

        $this->probe = $probe;
    }

    /**
     * The section, from cache when it has been measured.
     */
    #[Computed]
    public function section(): ProbeResult
    {
        return app(ProbeRunner::class)->run($this->probe);
    }

    /**
     * Measure now, in this request. Bounded by the probe's own budgets.
     */
    public function measure(): void
    {
        app(ProbeRunner::class)->warm($this->probe);

        $this->warming = false;
    }

    /**
     * Hand the measurement to the queue instead.
     */
    public function queueWarmup(): void
    {
        WarmSystemOverviewProbe::dispatch($this->probe);

        $this->warming = true;
    }

    /**
     * Render the card, to readers holding the overview ability only.
     *
     * Checking here rather than in mount() covers the update requests too: measuring a
     * section is a write to the shared cache, so it is not something an unauthorised
     * component id should be able to ask for.
     */
    public function render(): View
    {
        abort_unless(Gate::allows('view system overview'), 403);

        return view('livewire.system-overview-section', [
            'meta' => app(ProbeRegistry::class)->resolve($this->probe),
        ]);
    }
}
