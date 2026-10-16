<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Support\SystemOverview\PackageCatalog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * The package list that sits under the Dependencies card.
 *
 * Search and paging filter the rows the warm-up already cached, so a keystroke costs an
 * array walk rather than another megabyte of composer.lock. Rendered lazily, and only
 * once the card itself has been measured.
 */
final class SystemOverviewPackages extends Component
{
    use WithPagination;

    /**
     * @var int Rows shown per page.
     */
    private const PER_PAGE = 15;

    /**
     * @var string Free-text filter matched against package names and descriptions.
     */
    public string $search = '';

    /**
     * @var string Which lock file's packages to show: all, composer, or npm.
     */
    public string $source = 'all';

    /**
     * Narrow the list to one lock file.
     *
     * @param string $source Source key taken from PackageCatalog::SOURCES.
     */
    public function setSource(string $source): void
    {
        abort_unless(in_array($source, PackageCatalog::SOURCES, true), 404);

        $this->source = $source;
        $this->resetPage();
    }

    /**
     * Drop the search term and start again from the first page.
     */
    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Leave page one whenever the filter changes, or the page may hold no matches.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * The current page of packages.
     */
    #[Computed]
    public function packages(): LengthAwarePaginator
    {
        return app(PackageCatalog::class)->paginate($this->search, $this->source, $this->getPage(), self::PER_PAGE);
    }

    /**
     * Row counts per filter, for the tab labels.
     *
     * @return array<string, int>
     */
    #[Computed]
    public function totals(): array
    {
        $counts = app(PackageCatalog::class)->counts();

        return [
            'all' => $counts['composer'] + $counts['composer_dev'] + $counts['npm'] + $counts['npm_dev'],
            'composer' => $counts['composer'] + $counts['composer_dev'],
            'npm' => $counts['npm'] + $counts['npm_dev'],
        ];
    }

    /**
     * The skeleton shown while the list loads behind this lazy component.
     */
    public function placeholder(): View
    {
        return view('livewire.system-overview-packages.placeholder');
    }

    public function render(): View
    {
        abort_unless(Gate::allows('view system overview'), 403);

        return view('livewire.system-overview-packages', [
            'sources' => [
                'all' => __('messages.system_overview_packages_source_all'),
                'composer' => __('messages.system_overview_packages_source_composer'),
                'npm' => __('messages.system_overview_packages_source_npm'),
            ],
        ]);
    }
}
