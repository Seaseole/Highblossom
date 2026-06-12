<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Poll extends Model
{
    protected $fillable = [
        'question',
        'options',
        'allow_multiple',
        'show_results',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'allow_multiple' => 'boolean',
        'show_results' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    /**
     * Get the voting results as percentages.
     */
    public function getResultsAttribute(): array
    {
        // Use eager-loaded collection if available
        $votes = $this->votes;

        $voteCounts = $votes->groupBy('option_index')
            ->map(fn ($votes) => count($votes));

        $totalVotes = $votes->count();
        if ($totalVotes === 0) {
            return array_fill(0, count($this->options), 0);
        }

        $results = [];
        foreach ($this->options as $index => $_) {
            $count = $voteCounts->get($index, 0);
            $results[$index] = round(($count / $totalVotes) * 100, 1);
        }

        return $results;
    }

    /**
     * Get raw vote counts.
     */
    public function getVoteCountsAttribute(): array
    {
        $votes = $this->votes;

        $voteCounts = $votes->groupBy('option_index')
            ->map(fn ($votes) => count($votes));

        $results = [];
        foreach ($this->options as $index => $_) {
            $results[$index] = $voteCounts->get($index, 0);
        }

        return $results;
    }
}
