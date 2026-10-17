@php
    /** @var \App\Models\Post|null $post */
    $titleMax = \App\Models\Post::TITLE_MAX;
    $excerptMax = \App\Models\Post::EXCERPT_MAX;

    $field = 'w-full rounded-xl border bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]';
    $ok = 'border-gray-200 dark:border-white/10';
    $bad = 'border-red-500 dark:border-red-500';

    $titleClass = $field.' '.($errors->has('title') ? $bad : $ok);
    $excerptClass = $field.' '.($errors->has('excerpt') ? $bad : $ok);
@endphp

<div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 md:p-8 dark:border-white/10 dark:bg-[#0A0A0F]">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h2>

    <div x-data="charCount({{ $titleMax }})">
        <div class="mb-2 flex items-baseline justify-between gap-3">
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
            <span
                class="font-mono text-xs text-gray-400 dark:text-gray-500"
                :class="nearLimit() ? 'text-amber-500' : ''"
                x-text="count + ' / {{ $titleMax }}'"
            ></span>
        </div>

        <input
            id="title"
            type="text"
            name="title"
            maxlength="{{ $titleMax }}"
            x-on:input="count = $event.target.value.length"
            value="{{ old('title', $post?->title) }}"
            required
            autocomplete="off"
            class="{{ $titleClass }}"
        />

        @error('title')
            <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div x-data="charCount({{ $excerptMax }})">
        <div class="mb-2 flex items-baseline justify-between gap-3">
            <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Excerpt</label>
            <span
                class="font-mono text-xs text-gray-400 dark:text-gray-500"
                :class="nearLimit() ? 'text-amber-500' : ''"
                x-text="count + ' / {{ $excerptMax }}'"
            ></span>
        </div>

        <textarea
            id="excerpt"
            name="excerpt"
            rows="3"
            maxlength="{{ $excerptMax }}"
            x-on:input="count = $event.target.value.length"
            class="{{ $excerptClass }}"
        >{{ old('excerpt', $post?->excerpt) }}</textarea>

        @error('excerpt')
            <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror

        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Required to publish. Also used as the meta description.</p>
    </div>
</div>
