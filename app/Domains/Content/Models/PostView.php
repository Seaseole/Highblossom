<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PostView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'ip_address',
        'user_agent',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Record a view for a post (throttled by IP - max 1 view per hour per IP)
     */
    public static function record(Post $post, ?string $ip = null, ?string $userAgent = null): ?self
    {
        $ip = $ip ?? request()->ip();
        $userAgent = $userAgent ?? request()->userAgent();

        // Check for recent view from same IP (throttle to 1 per hour)
        $recentView = self::where('post_id', $post->id)
            ->where('ip_address', $ip)
            ->where('viewed_at', '>=', now()->subHour())
            ->exists();

        if ($recentView) {
            return null;
        }

        return self::create([
            'post_id' => $post->id,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'viewed_at' => now(),
        ]);
    }

    /**
     * Get view stats for a post
     */
    public static function statsForPost(Post $post): array
    {
        $total = self::where('post_id', $post->id)->count();
        $today = self::where('post_id', $post->id)
            ->whereDate('viewed_at', today())
            ->count();
        $thisWeek = self::where('post_id', $post->id)
            ->where('viewed_at', '>=', now()->subWeek())
            ->count();
        $thisMonth = self::where('post_id', $post->id)
            ->where('viewed_at', '>=', now()->subMonth())
            ->count();

        return [
            'total' => $total,
            'today' => $today,
            'week' => $thisWeek,
            'month' => $thisMonth,
        ];
    }
}
