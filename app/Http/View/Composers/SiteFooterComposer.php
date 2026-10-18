<?php

declare(strict_types=1);

namespace App\Http\View\Composers;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

/**
 * View composer that gives the site footer its live service links.
 *
 * Scoped to the footer partial only, so public pages that do not render it never
 * pay for the query.
 */
final readonly class SiteFooterComposer
{
    /** Number of service links shown in the footer column. */
    private const LINK_LIMIT = 5;

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        /** @var Collection<int, Service> $services */
        $services = Service::active()->ordered()->limit(self::LINK_LIMIT)->get(['title', 'slug']);

        $view->with('footerServices', $services);
    }
}
