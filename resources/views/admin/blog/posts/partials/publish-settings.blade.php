@php
    /** @var \App\Models\Post|null $post */
    $status = old('status', $post?->status ?? 'draft');
    $publishedAt = old('published_at', $post?->published_at?->format('Y-m-d\TH:i'));

    $field = 'w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]';
@endphp

<div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 md:p-8 dark:border-white/10 dark:bg-[#0A0A0F]">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Publish Settings</h2>

    <div>
        <label for="status" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
        <select id="status" name="status" required class="{{ $field }}">
            @foreach (\App\Models\Post::STATUSES as $option)
                <option value="{{ $option }}" @selected($status === $option)>{{ ucfirst($option) }}</option>
            @endforeach
        </select>

        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400" x-show="status === 'archived'" x-cloak>
            Archived posts keep their content but leave the public site. Restore one by setting it back to Draft or Published.
        </p>
    </div>

    <div>
        <label for="published_at" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Published at</label>
        <input
            id="published_at"
            type="datetime-local"
            name="published_at"
            value="{{ $publishedAt }}"
            class="{{ $field }}"
        />

        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Leave empty to publish immediately. A future date schedules the post, and it stays hidden from readers until then.
        </p>

        @error('published_at')
            <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div
        x-show="status === 'published'"
        x-cloak
        class="space-y-2 rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-white/10 dark:bg-white/5"
    >
        <p class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400">Ready to publish</p>

        <ul class="space-y-2 text-sm">
            @foreach ([
                'Content blocks' => 'blockCount',
                'Excerpt' => 'excerptLength',
                'Category' => 'categoryCount',
            ] as $label => $signal)
                <li class="flex items-center gap-2.5">
                    <span
                        class="h-2 w-2 flex-shrink-0 rounded-full transition-colors"
                        :class="{{ $signal }} ? 'bg-emerald-500' : 'bg-red-500'"
                    ></span>
                    <span :class="{{ $signal }} ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-white'">{{ $label }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <div
        x-show="blocked.length"
        x-cloak
        class="space-y-3 rounded-2xl border border-red-200 bg-red-50 p-4 dark:border-red-500/20 dark:bg-red-500/10"
        role="alert"
    >
        <p class="text-sm font-medium text-red-700 dark:text-red-400">This post is not ready to publish — it still needs:</p>

        <ul class="list-inside list-disc space-y-1 text-sm text-red-700 dark:text-red-400">
            <template x-for="item in blocked" :key="item">
                <li x-text="item"></li>
            </template>
        </ul>

        <button
            type="button"
            x-on:click="saveAsDraft()"
            class="rounded-full border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 transition-colors hover:bg-red-100 dark:border-red-500/30 dark:bg-white/5 dark:text-red-400 dark:hover:bg-red-500/10"
        >
            Save as draft instead
        </button>
    </div>

    @error('content')
        <p class="text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
