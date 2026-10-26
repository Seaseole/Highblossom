<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Quote;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * One read-only search across the admin records the requester is allowed to see.
 *
 * Exists for the top bar's command palette and nothing else. Every group is gated by the same
 * ability string its own index route is gated by, so the palette cannot surface a row that the
 * sidebar would have refused to link to — and a module the person cannot open is never even
 * queried, which keeps the answer honest without asking them to trust a filter they cannot see.
 *
 * The shape is deliberately narrow: a term of two to sixty characters, at most five rows per
 * module, and one `LIKE` per declared column. It is a jump box, not a reporting surface, so
 * there is no sort, no pagination, and no aggregate for anyone to reason about.
 */
final class SearchController extends Controller
{
    /**
     * Rows per module. Five is enough to find the thing you were about to type again.
     */
    private const LIMIT = 5;

    /**
     * Answer a search term with the groups it touches.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:60'],
        ]);

        $term = trim($validated['q']);
        $user = $request->user();

        if ($term === '' || $user === null) {
            return response()->json(['groups' => []]);
        }

        // A bare % typed into the box means "everything" to LIKE, which would return an
        // arbitrary five rows and read as a bug. Escaping the wildcards makes the person's own
        // characters the thing being looked for.
        $like = '%'.addcslashes($term, '%_\\').'%';

        $groups = [];

        foreach (self::modules() as $module) {
            if ($user->cannot($module['permission'])) {
                continue;
            }

            $items = $module['query']($like);

            if ($items !== []) {
                $groups[] = [
                    'group' => $module['label'],
                    'items' => $items,
                ];
            }
        }

        return response()->json(['groups' => $groups]);
    }

    /**
     * The searchable modules: what to look in, where a hit takes you, and the ability that
     * destination enforces.
     *
     * The gate named here is the one on the *result link*, not the one on the module's list
     * page, and they are not always the same ability: reading Posts is `view blog` while opening
     * one is `update blog`. A result you are not allowed to click is not a result, so the
     * stricter of the two is the one that decides whether the group is queried at all.
     *
     * @return array<int, array{label: string, permission: string, route: string, query: callable}>
     */
    public static function modules(): array
    {
        return [
            [
                'label' => 'Bookings',
                'permission' => 'view bookings',
                'route' => 'admin.bookings.show',
                'query' => fn (string $like) => self::rows(
                    Booking::query(),
                    ['reference', 'client_name', 'client_phone', 'vehicle_make', 'vehicle_model'],
                    $like,
                    fn (Booking $booking): array => [
                        'label' => $booking->reference ?: $booking->client_name,
                        'sub' => Str::headline((string) $booking->status).' · '.(string) $booking->scheduled_at?->format('d M Y'),
                        'url' => route('admin.bookings.show', $booking),
                    ],
                ),
            ],
            [
                'label' => 'Quotes',
                'permission' => 'view bookings',
                'route' => 'admin.quotes.show',
                'query' => fn (string $like) => self::rows(
                    Quote::query(),
                    ['name', 'email', 'phone', 'make_model', 'reg_number'],
                    $like,
                    fn (Quote $quote): array => [
                        'label' => $quote->name,
                        'sub' => trim((string) $quote->make_model.' '.(string) $quote->reg_number),
                        'url' => route('admin.quotes.show', $quote),
                    ],
                ),
            ],
            [
                'label' => 'Messages',
                'permission' => 'view contact messages',
                'route' => 'admin.contact-messages.show',
                'query' => fn (string $like) => self::rows(
                    ContactMessage::query(),
                    ['name', 'email', 'subject'],
                    $like,
                    fn (ContactMessage $message): array => [
                        'label' => $message->subject,
                        'sub' => $message->name.' · '.$message->email,
                        'url' => route('admin.contact-messages.show', $message),
                    ],
                ),
            ],
            [
                'label' => 'Posts',
                'permission' => 'update blog',
                'route' => 'admin.posts.edit',
                'query' => fn (string $like) => self::rows(
                    Post::query(),
                    ['title', 'slug'],
                    $like,
                    fn (Post $post): array => [
                        'label' => $post->title,
                        'sub' => (string) $post->status,
                        'url' => route('admin.posts.edit', $post),
                    ],
                ),
            ],
            [
                'label' => 'Services',
                'permission' => 'manage services',
                'route' => 'admin.services.edit',
                'query' => fn (string $like) => self::rows(
                    Service::query(),
                    ['title', 'slug'],
                    $like,
                    fn (Service $service): array => [
                        'label' => $service->title,
                        'sub' => (string) $service->short_description,
                        'url' => route('admin.services.edit', $service),
                    ],
                ),
            ],
            [
                'label' => 'Users',
                'permission' => 'manage users',
                'route' => 'admin.users.edit',
                'query' => fn (string $like) => self::rows(
                    User::query(),
                    ['name', 'email', 'username'],
                    $like,
                    fn (User $user): array => [
                        'label' => $user->name,
                        'sub' => $user->email,
                        'url' => route('admin.users.edit', $user),
                    ],
                ),
            ],
        ];
    }

    /**
     * Run one module's search and shape its hits.
     *
     * @param Builder<Model>                                                  $query
     * @param array<int, string>                                              $columns
     * @param callable(Model): array{label: string, sub: string, url: string} $present
     *
     * @return array<int, array{label: string, sub: string, url: string}>
     */
    private static function rows(Builder $query, array $columns, string $like, callable $present): array
    {
        return $query
            ->where(fn (Builder $inner) => collect($columns)->each(
                fn (string $column) => $inner->orWhere($column, 'like', $like),
            ))
            ->latest()
            ->limit(self::LIMIT)
            ->get()
            ->map($present)
            ->all();
    }
}
