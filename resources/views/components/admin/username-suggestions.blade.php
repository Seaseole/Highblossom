@props([
    'ignoreUserId' => null,
])

@if (config('ai.enabled'))
    {{-- Shared by the staff create/edit forms and the profile username field.
         Only names that passed the server-side availability check are shown. --}}
    <div
        x-data="usernameSuggestions('{{ route('admin.username-suggestions') }}', {{ $ignoreUserId ? (int) $ignoreUserId : 'null' }})"
        class="mt-2"
    >
        <button
            type="button"
            x-on:click="suggest()"
            :disabled="loading"
            class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 transition-colors hover:border-gray-900 disabled:opacity-50 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:border-white"
        >
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3z" />
            </svg>
            <span x-show="! loading">Suggest available usernames with AI</span>
            <span x-show="loading" x-cloak>Suggesting…</span>
        </button>

        <p
            x-show="message"
            x-cloak
            x-text="message"
            role="status"
            aria-live="polite"
            class="mt-1.5 text-xs font-medium text-amber-600 dark:text-amber-400"
        ></p>

        <div x-show="usernames.length" x-cloak class="mt-2 flex flex-wrap gap-2" aria-label="Available usernames">
            <template x-for="(name, index) in usernames" :key="index">
                <button
                    type="button"
                    x-on:click="apply(name)"
                    x-text="name"
                    class="rounded-full border border-gray-200 bg-gray-50 px-3 py-1.5 font-mono text-xs text-gray-700 transition-all hover:border-gray-900 hover:bg-gray-900 hover:text-white dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:border-white dark:hover:bg-white dark:hover:text-gray-900"
                ></button>
            </template>
        </div>

        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
            Only usernames not already in use are shown. Availability is checked again when you save.
        </p>
    </div>
@endif
