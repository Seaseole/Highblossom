<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\GalleryImage;
use App\Models\Nudge;
use App\Models\Post;
use App\Models\Quote;
use App\Models\SeoStaticRoute;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

/**
 * Measures local content-hygiene signals and keeps one nudge row per rule.
 *
 * Nothing here calls a provider: messages are phrased from templates at scan
 * time. A rule owns its row by key; the fingerprint hashes the signal payload
 * so an unchanged signal never churns the row. Dismissal is sticky until the
 * signal fully clears (resolved), after which a recurrence opens fresh.
 */
final class NudgeService
{
    /**
     * Run every rule and reconcile the store. Returns created/updated/resolved counts.
     *
     * @return array{created: int, updated: int, resolved: int}
     */
    public function scan(): array
    {
        $counts = ['created' => 0, 'updated' => 0, 'resolved' => 0];

        foreach ($this->rules() as $key => $rule) {
            $signal = $rule();
            $row = Nudge::query()->where('key', $key)->first();

            if ($signal === null) {
                if ($row !== null && $row->status !== Nudge::STATUS_RESOLVED) {
                    $row->update(['status' => Nudge::STATUS_RESOLVED, 'snoozed_until' => null]);
                    $counts['resolved']++;
                }

                continue;
            }

            $fingerprint = hash('sha256', $key.'|'.json_encode($signal['payload']));

            if ($row === null) {
                Nudge::query()->create([
                    'key' => $key,
                    'fingerprint' => $fingerprint,
                    'severity' => $signal['severity'],
                    'message' => $signal['message'],
                    'action_url' => $signal['action_url'],
                    'action_label' => $signal['action_label'],
                    'permission' => $signal['permission'],
                    'status' => Nudge::STATUS_OPEN,
                ]);
                $counts['created']++;

                continue;
            }

            // A dismissed row stays dismissed for this occurrence of the signal.
            if ($row->status === Nudge::STATUS_DISMISSED) {
                continue;
            }

            if ($row->status === Nudge::STATUS_SNOOZED && $row->snoozed_until?->isFuture()) {
                continue;
            }

            if ($row->status === Nudge::STATUS_OPEN && $row->fingerprint === $fingerprint) {
                continue;
            }

            $row->update([
                'fingerprint' => $fingerprint,
                'severity' => $signal['severity'],
                'message' => $signal['message'],
                'action_url' => $signal['action_url'],
                'action_label' => $signal['action_label'],
                'permission' => $signal['permission'],
                'status' => Nudge::STATUS_OPEN,
                'snoozed_until' => null,
            ]);
            $counts['updated']++;
        }

        return $counts;
    }

    /**
     * The nudges this viewer may act on, most severe first, capped so the desk
     * stays a desk. Expired snoozes count as open without waiting for a scan.
     *
     * @return Collection<int, Nudge>
     */
    public function visible(): Collection
    {
        return Nudge::query()
            ->active()
            ->get()
            ->filter(fn (Nudge $nudge): bool => $nudge->permission === null || Gate::allows($nudge->permission))
            ->sortBy([
                fn (Nudge $a, Nudge $b): int => ($b->severity === Nudge::SEVERITY_WARN ? 1 : 0) <=> ($a->severity === Nudge::SEVERITY_WARN ? 1 : 0),
                fn (Nudge $a, Nudge $b): int => $a->created_at <=> $b->created_at,
            ])
            ->take((int) config('ai.nudges.max_active', 3))
            ->values();
    }

    /**
     * The rule catalogue, keyed by the nudge key each rule owns.
     *
     * @return array<string, callable(): ?array{message: string, action_url: string, action_label: string, severity: string, permission: string, payload: mixed}>
     */
    private function rules(): array
    {
        return [
            'quotes_stale' => fn (): ?array => $this->staleQuotes(),
            'bookings_unconfirmed' => fn (): ?array => $this->unconfirmedBookings(),
            'drafts_stale' => fn (): ?array => $this->staleDrafts(),
            'seo_missing' => fn (): ?array => $this->missingSeo(),
            'services_no_image' => fn (): ?array => $this->servicesWithoutImages(),
            'gallery_missing_description' => fn (): ?array => $this->galleryWithoutDescriptions(),
            'blog_quiet' => fn (): ?array => $this->quietBlog(),
        ];
    }

    /**
     * Pending quotes nobody has answered inside the stale window. The desk lists
     * them live; this nudge is the aging threshold, and it also lives on the overview.
     *
     * @return ?array{message: string, action_url: string, action_label: string, severity: string, permission: string, payload: mixed}
     */
    private function staleQuotes(): ?array
    {
        $count = Quote::query()
            ->where('status', 'pending')
            ->where('created_at', '<=', now()->subHours((int) config('ai.nudges.quote_stale_hours', 48)))
            ->count();

        if ($count === 0) {
            return null;
        }

        return [
            'message' => trans_choice('admin-nudges.quotes_stale', $count, ['count' => $count]),
            'action_url' => route('admin.quotes.index', ['status' => 'pending']),
            'action_label' => __('admin-nudges.review_quotes'),
            'severity' => Nudge::SEVERITY_WARN,
            'permission' => 'view bookings',
            'payload' => ['count' => $count],
        ];
    }

    /**
     * Bookings inside the forecast window that the shop has not confirmed yet.
     *
     * @return ?array{message: string, action_url: string, action_label: string, severity: string, permission: string, payload: mixed}
     */
    private function unconfirmedBookings(): ?array
    {
        $window = (int) config('ai.nudges.booking_window_days', 7);
        $query = Booking::query()
            ->where('status', 'pending')
            ->whereBetween('scheduled_at', [now(), now()->addDays($window)]);

        $count = (clone $query)->count();

        if ($count === 0) {
            return null;
        }

        $soon = (clone $query)->where('scheduled_at', '<=', now()->addHours(48))->exists();

        return [
            'message' => trans_choice('admin-nudges.bookings_unconfirmed', $count, ['count' => $count, 'days' => $window]),
            'action_url' => route('admin.bookings.index'),
            'action_label' => __('admin-nudges.review_bookings'),
            'severity' => $soon ? Nudge::SEVERITY_WARN : Nudge::SEVERITY_INFO,
            'permission' => 'view bookings',
            'payload' => ['count' => $count, 'soon' => $soon],
        ];
    }

    /**
     * Drafts that have not been touched in a week.
     *
     * @return ?array{message: string, action_url: string, action_label: string, severity: string, permission: string, payload: mixed}
     */
    private function staleDrafts(): ?array
    {
        $count = Post::query()
            ->where('status', 'draft')
            ->where('updated_at', '<=', now()->subDays((int) config('ai.nudges.draft_stale_days', 7)))
            ->count();

        if ($count === 0) {
            return null;
        }

        return [
            'message' => trans_choice('admin-nudges.drafts_stale', $count, ['count' => $count]),
            'action_url' => route('admin.posts.index'),
            'action_label' => __('admin-nudges.review_drafts'),
            'severity' => Nudge::SEVERITY_INFO,
            'permission' => 'view blog',
            'payload' => ['count' => $count],
        ];
    }

    /**
     * Allowlisted static routes with no SEO row, or a row missing its title or
     * description. Route names come from config, never from the request.
     *
     * @return ?array{message: string, action_url: string, action_label: string, severity: string, permission: string, payload: mixed}
     */
    private function missingSeo(): ?array
    {
        $routes = (array) config('seo.static_routes', []);

        if ($routes === []) {
            return null;
        }

        $configured = SeoStaticRoute::query()
            ->whereIn('route_name', $routes)
            ->get(['route_name', 'meta_title', 'meta_description'])
            ->keyBy('route_name');

        $count = 0;

        foreach ($routes as $routeName) {
            $row = $configured->get($routeName);

            if ($row === null || trim((string) $row->meta_title) === '' || trim((string) $row->meta_description) === '') {
                $count++;
            }
        }

        if ($count === 0) {
            return null;
        }

        return [
            'message' => trans_choice('admin-nudges.seo_missing', $count, ['count' => $count]),
            'action_url' => route('admin.seo.static-routes'),
            'action_label' => __('admin-nudges.configure_seo'),
            'severity' => Nudge::SEVERITY_INFO,
            'permission' => 'manage seo',
            'payload' => ['count' => $count],
        ];
    }

    /**
     * Active services with neither image column filled.
     *
     * @return ?array{message: string, action_url: string, action_label: string, severity: string, permission: string, payload: mixed}
     */
    private function servicesWithoutImages(): ?array
    {
        $count = Service::query()
            ->where('is_active', true)
            ->where(fn ($query) => $query->whereNull('image_url')->orWhere('image_url', ''))
            ->where(fn ($query) => $query->whereNull('image_path')->orWhere('image_path', ''))
            ->count();

        if ($count === 0) {
            return null;
        }

        return [
            'message' => trans_choice('admin-nudges.services_no_image', $count, ['count' => $count]),
            'action_url' => route('admin.services.index'),
            'action_label' => __('admin-nudges.review_services'),
            'severity' => Nudge::SEVERITY_INFO,
            'permission' => 'manage services',
            'payload' => ['count' => $count],
        ];
    }

    /**
     * Public gallery rows without a description. Alt text is not measured: the
     * public gallery renders alt from the title, which the form already requires.
     *
     * @return ?array{message: string, action_url: string, action_label: string, severity: string, permission: string, payload: mixed}
     */
    private function galleryWithoutDescriptions(): ?array
    {
        $count = GalleryImage::query()
            ->where('source', GalleryImage::SOURCE_GALLERY)
            ->where('is_active', true)
            ->where(fn ($query) => $query->whereNull('description')->orWhere('description', ''))
            ->count();

        if ($count === 0) {
            return null;
        }

        return [
            'message' => trans_choice('admin-nudges.gallery_missing_description', $count, ['count' => $count]),
            'action_url' => route('admin.gallery.index'),
            'action_label' => __('admin-nudges.review_gallery'),
            'severity' => Nudge::SEVERITY_INFO,
            'permission' => 'manage gallery',
            'payload' => ['count' => $count],
        ];
    }

    /**
     * A blog that has published before but nothing inside the quiet window. The
     * payload is the latest post id, so a new post re-arms the rule for the next
     * quiet stretch instead of mutating a dismissed row.
     *
     * @return ?array{message: string, action_url: string, action_label: string, severity: string, permission: string, payload: mixed}
     */
    private function quietBlog(): ?array
    {
        $latest = Post::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->first(['id', 'published_at']);

        if ($latest === null || $latest->published_at->isAfter(now()->subDays((int) config('ai.nudges.blog_quiet_days', 30)))) {
            return null;
        }

        return [
            'message' => __('admin-nudges.blog_quiet'),
            'action_url' => route('admin.posts.create'),
            'action_label' => __('admin-nudges.write_post'),
            'severity' => Nudge::SEVERITY_INFO,
            'permission' => 'view blog',
            'payload' => ['latest_post' => $latest->id],
        ];
    }
}
