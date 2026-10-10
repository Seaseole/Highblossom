{{-- The root stays mounted so Livewire keeps owning the dismiss button; the prompt itself is conditional. --}}
<div role="status" @if (! $visible) hidden @endif class="mb-8 flex flex-col gap-4 rounded-3xl border border-amber-200 bg-amber-50 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-amber-400/25 dark:bg-amber-400/5">
    @if ($visible)
        <div class="flex items-start gap-4">
            <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0 1 19.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 0 0 4.5 10.5a7.464 7.464 0 0 1-1.15 3.993m1.989 3.559A11.209 11.209 0 0 0 8.25 10.5a3.75 3.75 0 1 1 7.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 0 1-3.6 9.75m6.633-4.596a18.666 18.666 0 0 1-2.485 5.33" />
                </svg>
            </span>
            <div class="space-y-1">
                <h2 class="text-sm font-semibold text-amber-900 dark:text-amber-200">{{ __('auth.passkey_reenroll.title') }}</h2>
                <p class="text-sm text-amber-800/80 dark:text-amber-200/70">{{ __('auth.passkey_reenroll.message') }}</p>
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-3">
            <a
                href="{{ route('admin.profile.index', ['tab' => 'passkeys']) }}"
                class="rounded-xl bg-amber-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-amber-700 focus:ring-4 focus:ring-amber-500/20 focus:outline-none"
            >{{ __('auth.passkey_reenroll.action') }}</a>
            <button
                type="button"
                wire:click="dismiss"
                class="rounded-xl px-4 py-2 text-sm font-semibold text-amber-800/80 transition-colors hover:text-amber-900 focus:ring-4 focus:ring-amber-500/20 focus:outline-none dark:text-amber-200/70"
            >{{ __('auth.passkey_reenroll.dismiss') }}</button>
        </div>
    @endif
</div>
