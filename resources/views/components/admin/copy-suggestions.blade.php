@props([
    'endpoint',
    'target' => '',
    'resultKey' => 'suggestions',
    'fields' => [],
    'label' => 'Suggest with AI',
    'busyLabel' => 'Suggesting…',
    'listLabel' => 'Suggestions',
    'field' => null,
    'seoRouteId' => null,
    'seoRouteName' => null,
    'block' => null,
    'blockFields' => [],
    'editor' => null,
])

@if (config('ai.enabled'))
    @php
        $tones = (array) config('ai.tones', []);
        $defaultTone = (string) app(\App\Services\Settings\SettingsManager::class)->get('ai_tone', 'professional');

        if (! isset($tones[$defaultTone])) {
            $defaultTone = array_key_first($tones) ?? '';
        }
    @endphp
    {{-- Explicit action, never a keystroke trigger: every click spends tokens and
         sends a redacted summary of the form to a third-party provider. Motion
         reuses the .ai-title-* arc from admin.css, which collapses to static
         colour under reduced-motion. Configuration lives in data attributes so
         no JSON is ever echoed into a Blade attribute. In block mode the click
         handlers receive the surrounding Alpine block object ($block is the
         server-controlled JS expression 'block' or 'nb'), so values are read
         from and written back to the builder's live state. --}}
    <div
        x-data="copySuggestions"
        data-endpoint="{{ route($endpoint) }}"
        data-target="{{ $target }}"
        data-key="{{ $resultKey }}"
        data-fields="{{ implode(',', $fields) }}"
        data-label="{{ $listLabel }}"
        data-block-fields="{{ implode(',', $blockFields) }}"
        data-tone="{{ $defaultTone }}"
        @if ($field) data-field="{{ $field }}" @endif
        @if ($seoRouteId) data-route-id="{{ (int) $seoRouteId }}" @endif
        @if ($seoRouteName) data-route-name="{{ $seoRouteName }}" @endif
        @if ($editor) data-editor="{{ $editor }}" @endif
        :aria-busy="loading ? 'true' : 'false'"
        class="ai-title-suggestions mt-3"
    >
        @if ($block && count($blockFields) > 1)
            <label class="mb-2 block text-xs font-medium text-gray-500 dark:text-gray-400">
                Field to suggest
                <select
                    x-model="blockField"
                    class="mt-1 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:text-gray-100 dark:focus:ring-white"
                >
                    @foreach ($blockFields as $blockField)
                        <option value="{{ $blockField }}">{{ ucfirst(str_replace('_', ' ', $blockField)) }}</option>
                    @endforeach
                </select>
            </label>
        @endif

        <div class="flex flex-wrap items-center gap-2">
            @if (count($tones) > 1)
                <select
                    x-model="tone"
                    aria-label="Suggestion voice"
                    title="Suggestion voice"
                    class="min-h-[44px] rounded-full border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:focus:ring-white"
                >
                    @foreach ($tones as $toneKey => $tone)
                        <option value="{{ $toneKey }}" @selected($toneKey === $defaultTone)>{{ $tone['label'] }}</option>
                    @endforeach
                </select>
            @endif

            <button
                type="button"
                x-on:click="suggest({{ $block ?? 'null' }})"
                :disabled="loading"
                :class="{ 'is-loading': loading }"
                class="ai-title-trigger inline-flex min-h-[44px] items-center gap-1.5 rounded-full border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:border-gray-900 disabled:opacity-50 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:border-white"
            >
                <svg class="ai-title-trigger-spark h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3z" />
                </svg>
                <span x-show="! loading">{{ $label }}</span>
                <span x-show="loading" x-cloak class="ai-title-trigger-busy">
                    <span class="ai-title-dots" aria-hidden="true"><i></i><i></i><i></i></span>
                    {{ $busyLabel }}
                </span>
            </button>
        </div>

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
            x-show="suggestions.length"
            x-cloak
            class="ai-title-list mt-3 space-y-2"
            :aria-label="listLabel"
        >
            <template x-for="(suggestion, index) in suggestions" :key="run + '-' + index">
                <button
                    type="button"
                    x-on:click="apply(suggestion, {{ $block ?? 'null' }})"
                    x-text="suggestion"
                    :style="'--ai-index:' + index + ';--ai-hue:' + ((index * 34 + 250) % 360)"
                    class="ai-title-option flex min-h-[44px] w-full items-center rounded-xl px-4 py-2.5 text-left text-sm"
                ></button>
            </template>
        </div>

        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Suggestions come from a third-party AI service. A redacted summary of this form is sent for processing.
        </p>
    </div>
@endif