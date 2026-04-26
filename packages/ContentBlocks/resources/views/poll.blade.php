@php
    $pollId = $poll_id ?? null;
    $question = $question ?? '';
    $options = $options ?? [];
    $allowMultiple = $allow_multiple ?? false;
    $showResults = $show_results ?? false;
    $uniqueId = 'cb-poll-' . uniqid();
@endphp

<div class="cb-poll" x-data="{
    selectedOptions: [],
    hasVoted: false,
    results: @if($showResults && $pollId) {{ \App\Models\Poll::find($pollId)?->results ?? [] }} @else [] @endif,
    vote() {
        if (this.selectedOptions.length === 0) return;
        
        this.hasVoted = true;
        
        @if($pollId)
        $wire.post('/api/content-blocks/poll/' . {{ $pollId }}, {
            options: this.selectedOptions
        }).then(response => {
            this.results = response.results;
        });
        @endif
    }
}">
    <div class="cb-poll__question">{{ $question }}</div>

    <div class="cb-poll__options" x-show="!hasVoted || !{{ $showResults ? 'true' : 'false' }}">
        @foreach($options as $index => $option)
            <label class="cb-poll__option">
                <input
                    type="{{ $allowMultiple ? 'checkbox' : 'radio' }}"
                    name="{{ $uniqueId }}"
                    value="{{ $index }}"
                    x-model="selectedOptions"
                    @if(!$allowMultiple) x-model.number @endif
                    class="cb-poll__input"
                >
                <span class="cb-poll__option-label">{{ $option }}</span>
            </label>
        @endforeach
    </div>

    <button
        type="button"
        @click="vote"
        :disabled="selectedOptions.length === 0"
        x-show="!hasVoted"
        class="cb-poll__submit"
    >
        Vote
    </button>

    <div class="cb-poll__results" x-show="hasVoted && {{ $showResults ? 'true' : 'false' }}">
        @foreach($options as $index => $option)
            <div class="cb-poll__result">
                <div class="cb-poll__result-label">{{ $option }}</div>
                <div class="cb-poll__result-bar">
                    <div
                        class="cb-poll__result-fill"
                        :style="{ width: (results[{{ $index }}] || 0) + '%' }"
                    ></div>
                </div>
                <div class="cb-poll__result-count" x-text="(results[{{ $index }}] || 0) + '%'"></div>
            </div>
        @endforeach
    </div>
</div>
