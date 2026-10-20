{{--
    Security → password policy.

    The form writes the two length bounds and the composition toggles that
    PasswordPolicy reads for every password form in the application. The guard rails are
    server-side (PasswordPolicyRequest); the range text under each control is the same
    floor and ceiling, so a screenshot of this page cannot promise something the
    validator does not enforce.
--}}
@php
    $minFloor = \App\Services\PasswordPolicy::MIN_FLOOR;
    $maxFloor = \App\Services\PasswordPolicy::MAX_FLOOR;
    $ceiling = \App\Services\PasswordPolicy::MAX_CEILING;
@endphp

<x-layouts::admin title="Security">
    <div
        class="mx-auto max-w-3xl space-y-8 py-6 sm:py-10"
        x-data="{
            min: 0,
            max: 0,
            mixed: false,
            numbers: false,
            symbols: false,
            breached: false,
            init() {
                const d = this.$el.dataset;
                this.min = parseInt(d.min, 10);
                this.max = parseInt(d.max, 10);
                this.mixed = d.mixed === '1';
                this.numbers = d.numbers === '1';
                this.symbols = d.symbols === '1';
                this.breached = d.breached === '1';
            },
            get preview() {
                const parts = [`${this.min} to ${this.max} characters`];
                if (this.mixed) parts.push('one uppercase and one lowercase letter');
                if (this.numbers) parts.push('at least one number');
                if (this.symbols) parts.push('at least one symbol');
                if (this.breached) parts.push('not seen in a known data leak');
                if (parts.length === 1) return parts[0];
                return parts.slice(0, -1).join(', ') + ' and ' + parts[parts.length - 1];
            },
            get crossed() { return this.min > this.max },
        }"
        data-min="{{ $policy['min'] }}"
        data-max="{{ $policy['max'] }}"
        data-mixed="{{ $policy['mixed_case'] ? '1' : '0' }}"
        data-numbers="{{ $policy['numbers'] ? '1' : '0' }}"
        data-symbols="{{ $policy['symbols'] ? '1' : '0' }}"
        data-breached="{{ $policy['breached'] ? '1' : '0' }}"
    >
        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="shield-check" size="sm" class="text-gray-500 dark:text-gray-400" />
                    <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Security</h1>
                </div>
                <p class="text-gray-500 dark:text-gray-400">Set the password rules every sign-up, password reset and password change is held to.</p>
            </div>
            <button
                type="submit"
                form="password-policy-form"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-700"
            >
                <x-ui.icon name="check" size="sm" />
                Save Changes
            </button>
        </div>

        <form id="password-policy-form" method="POST" action="{{ route('admin.password-policy.update') }}" class="space-y-6" novalidate>
            @csrf
            @method('PUT')

            {{-- Active policy --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <p class="text-xs font-semibold uppercase tracking-[0.06em] text-gray-500 dark:text-gray-400">Applied right now</p>
                <p class="mt-2 text-sm text-gray-900 dark:text-white" x-text="preview">{{ $summary }}</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Existing passwords are never re-checked at sign-in, so shortening the rule does not lock anyone out.
                    It applies the next time a password is created or changed.
                </p>
            </div>

            {{-- Length bounds --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Length</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    bcrypt silently truncates at {{ $ceiling }} bytes, so a longer maximum would let two different
                    passwords hash to the same value and both sign in. That is why {{ $ceiling }} is the ceiling.
                </p>

                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="password_min_length" class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Minimum length</label>
                        <input
                            type="number"
                            id="password_min_length"
                            name="password_min_length"
                            inputmode="numeric"
                            x-model.number="min"
                            value="{{ old('password_min_length', $policy['min']) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 transition-all outline-none focus:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-white"
                        />
                        @error('password_min_length')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Allowed: {{ $minFloor }} to {{ $ceiling }}. Never below {{ $minFloor }}.</p>
                    </div>

                    <div>
                        <label for="password_max_length" class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Maximum length</label>
                        <input
                            type="number"
                            id="password_max_length"
                            name="password_max_length"
                            inputmode="numeric"
                            x-model.number="max"
                            value="{{ old('password_max_length', $policy['max']) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 transition-all outline-none focus:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-white"
                        />
                        @error('password_max_length')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Allowed: {{ $maxFloor }} to {{ $ceiling }}. Must be at least the minimum.</p>
                    </div>
                </div>

                <p
                    x-show="crossed"
                    x-cloak
                    class="mt-4 flex items-start gap-2 rounded-xl border border-orange-300/60 bg-orange-50 px-4 py-3 text-sm text-orange-800 dark:border-orange-400/30 dark:bg-orange-400/10 dark:text-orange-300"
                >
                    <x-ui.icon name="exclamation-triangle" size="xs" class="mt-0.5 shrink-0" />
                    <span>The minimum is above the maximum. No password could satisfy both, so this will be rejected on save.</span>
                </p>
            </div>

            {{-- Composition toggles --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Composition</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Length does most of the work. Each extra class of character costs the person setting the password more memory than it costs an attacker time.</p>

                <div class="mt-5 space-y-4">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="password_require_mixed_case" value="1" x-model="mixed" {{ old('password_require_mixed_case', $policy['mixed_case']) ? 'checked' : '' }} class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 dark:border-white/20 dark:bg-white/5" />
                        <span class="text-sm text-gray-900 dark:text-white">
                            Require uppercase and lowercase
                            <span class="mt-0.5 block text-gray-500 dark:text-gray-400">Both an A-Z and an a-z character.</span>
                        </span>
                    </label>

                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="password_require_numbers" value="1" x-model="numbers" {{ old('password_require_numbers', $policy['numbers']) ? 'checked' : '' }} class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 dark:border-white/20 dark:bg-white/5" />
                        <span class="text-sm text-gray-900 dark:text-white">
                            Require a number
                            <span class="mt-0.5 block text-gray-500 dark:text-gray-400">At least one digit.</span>
                        </span>
                    </label>

                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="password_require_symbols" value="1" x-model="symbols" {{ old('password_require_symbols', $policy['symbols']) ? 'checked' : '' }} class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 dark:border-white/20 dark:bg-white/5" />
                        <span class="text-sm text-gray-900 dark:text-white">
                            Require a symbol
                            <span class="mt-0.5 block text-gray-500 dark:text-gray-400">At least one character that is not a letter or a digit.</span>
                        </span>
                    </label>

                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="password_check_breached" value="1" x-model="breached" {{ old('password_check_breached', $policy['breached']) ? 'checked' : '' }} class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 dark:border-white/20 dark:bg-white/5" />
                        <span class="text-sm text-gray-900 dark:text-white">
                            Rejected breached passwords
                            <span class="mt-0.5 block text-gray-500 dark:text-gray-400">
                                Sends the first 5 characters of the SHA-1 hash of each new password to the Have I Been Pwned
                                k-anonymity service when the password is set. It is an outbound call on a user-facing action:
                                if that service is unreachable, setting a password fails. The password itself is never sent.
                            </span>
                        </span>
                    </label>
                </div>
            </div>
        </form>

        {{-- Audit trail --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Recent changes</h2>
            @if ($trail === [])
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nothing has been saved yet, so this policy is still the default.</p>
            @else
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Every save records who changed which value and from what.</p>
                <ul class="mt-4 divide-y divide-gray-200 dark:divide-white/10">
                    @foreach ($trail as $entry)
                        <li class="py-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $entry['actor'] }}</p>
                            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ $entry['changes'] }}</p>
                            <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">{{ $entry['at'] }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts::admin>
