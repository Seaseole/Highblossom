@php
    $pollId = $poll_id ?? null;
    $question = $question ?? '';
    $options = $options ?? [];
    $allowMultiple = $allow_multiple ?? false;
    $showResults = $show_results ?? true;
    $uniqueId = 'cb-poll-' . ($pollId ?? uniqid());

    // Use session check for vote status
    $hasVoted = $pollId ? session()->has("poll_voted_{$pollId}") : false;

    // Fetch results if they should be shown
    $results = [];
    if ($pollId) {
        $poll = \App\Models\Poll::with('votes')->find($pollId);
        if ($poll && ($poll->show_results || $hasVoted)) {
            $results = $poll->results;
        }
    }
@endphp

<div class="cb-poll bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-3xl p-6 shadow-sm"
     x-data="{
        selectedOptions: [],
        selectedOption: null,
        allowMultiple: {{ $allowMultiple ? 'true' : 'false' }},
        hasVoted: {{ $hasVoted ? 'true' : 'false' }},
        results: @js($results),
        isVoting: false,
        error: null,
        
        get hasSelection() {
            return this.allowMultiple
                ? this.selectedOptions.length > 0
                : this.selectedOption !== null;
        },

        async vote() {
            if (!this.hasSelection) return;

            this.isVoting = true;
            this.error = null;

            try {
                const url = '{{ route('poll.vote', ['poll' => $pollId ?? 0]) }}';
                const opts = this.allowMultiple
                    ? this.selectedOptions.map(Number)
                    : [Number(this.selectedOption)];
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        options: opts
                    })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    this.results = data.results;
                    this.hasVoted = true;
                } else {
                    this.error = data.error || 'Something went wrong';
                    if (data.results) {
                        this.results = data.results;
                        this.hasVoted = true;
                    }
                }
            } catch (e) {
                this.error = 'Failed to submit vote. Please try again.';
            } finally {
                this.isVoting = false;
            }
        }
     }">
    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">{{ $question }}</h3>

    <!-- Form Section -->
    <div class="space-y-4 mb-6" x-show="!hasVoted">
        @foreach($options as $index => $option)
            <label class="group flex items-center gap-3 p-4 bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl cursor-pointer hover:border-gray-900 dark:hover:border-white transition-all">
                <input
                    type="{{ $allowMultiple ? 'checkbox' : 'radio' }}"
                    name="{{ $uniqueId }}"
                    value="{{ $index }}"
                    x-model="{{ $allowMultiple ? 'selectedOptions' : 'selectedOption' }}"
                    class="w-5 h-5 rounded-full border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white transition-all"
                >
                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $option }}</span>
            </label>
        @endforeach
    </div>

    <div x-show="error" x-text="error" class="mb-4 text-sm text-red-500 bg-red-50 dark:bg-red-900/20 p-3 rounded-xl"></div>

    <!-- Vote Button -->
    <button
        type="button"
        @click="vote"
        :disabled="!hasSelection || isVoting"
        x-show="!hasVoted"
        class="w-full py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-semibold rounded-2xl hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-3"
    >
        <span x-show="isVoting" x-cloak class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></span>
        <span x-show="isVoting" x-cloak>Submitting...</span>
        <span x-show="!isVoting">Submit Vote</span>
    </button>

    <!-- Results Section -->
    <div class="space-y-6" x-show="hasVoted && {{ $showResults ? 'true' : 'false' }}" x-transition>
        @foreach($options as $index => $option)
            <div class="space-y-2">
                <div class="flex justify-between items-center text-sm">
                    <span class="font-medium text-gray-900 dark:text-white">{{ $option }}</span>
                    <span class="text-gray-500 dark:text-gray-400 font-mono" x-text="(results[{{ $index }}] || 0) + '%'"></span>
                </div>
                <div class="h-3 bg-gray-100 dark:bg-white/10 rounded-full overflow-hidden">
                    <div
                        class="h-full bg-gray-900 dark:bg-white transition-all duration-1000 ease-out"
                        :style="{ width: (results[{{ $index }}] || 0) + '%' }"
                    ></div>
                </div>
            </div>
        @endforeach
        <p class="text-sm text-center text-gray-500 dark:text-gray-400 mt-4">
            {{ $showResults ? 'Thank you for voting!' : 'Thank you for your vote!' }}
        </p>
    </div>
</div>
