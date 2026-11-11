@if (config('ai.enabled'))
    {{-- Explicit action, never a keystroke trigger: every click spends tokens and
         sends a redacted summary of the draft to a third-party provider. The
         motion is scoped to .ai-title-suggestions in admin.css; it is decorative
         and collapses to a static coloured state under reduced-motion. --}}
    <div
        x-data="titleSuggestions('{{ route('admin.posts.title-suggestions') }}')"
        :aria-busy="loading ? 'true' : 'false'"
        class="ai-title-suggestions mt-3"
    >
        <button
            type="button"
            x-on:click="suggest()"
            :disabled="loading"
            :class="{ 'is-loading': loading }"
            class="ai-title-trigger inline-flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:border-gray-900 disabled:opacity-50 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:border-white"
        >
            <svg class="ai-title-trigger-spark h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3z" />
            </svg>
            <span x-show="! loading">Suggest titles with AI</span>
            <span x-show="loading" x-cloak class="ai-title-trigger-busy">
                <span class="ai-title-dots" aria-hidden="true"><i></i><i></i><i></i></span>
                Weaving titles…
            </span>
        </button>

        <div
            x-show="loading"
            x-cloak
            x-transition.opacity.duration.200ms
            class="ai-title-loading mt-3"
            role="status"
            aria-live="polite"
        >
            <div class="ai-title-loading-track" aria-hidden="true">
                <div class="ai-title-loading-sweep"></div>
            </div>
            <p class="ai-title-loading-copy">Reading the draft · shaping headlines</p>
        </div>

        <p
            x-show="message"
            x-cloak
            x-text="message"
            role="status"
            aria-live="polite"
            class="ai-title-message mt-2 text-xs font-medium text-amber-600 dark:text-amber-400"
        ></p>

        <div
            x-show="titles.length"
            x-cloak
            class="ai-title-list mt-3 flex flex-wrap gap-2"
            aria-label="Suggested titles"
        >
            <template x-for="(title, index) in titles" :key="run + '-' + index">
                <button
                    type="button"
                    x-on:click="apply(title)"
                    x-text="title"
                    :style="'--ai-index:' + index + ';--ai-hue:' + ((index * 34 + 250) % 360)"
                    class="ai-title-option max-w-full rounded-full px-4 py-2 text-left text-sm"
                ></button>
            </template>
        </div>

        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Suggestions come from a third-party AI service. A redacted summary of this draft is sent for processing.
        </p>
    </div>
@endif
