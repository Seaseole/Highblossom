{{--
    Two-factor challenge.

    Alpine drives the six-box code entry, the auto-submit at six digits and the
    switch to recovery codes, so this is the one auth screen that boots it — from
    the bundled auth-alpine entry rather than a CDN build.

    Field names (`code`, `recovery_code`), the form action and the auto-submit are
    Fortify's contract. Only the presentation changed.
--}}
<x-layouts::auth
    :title="__('auth.two_factor.title')"
    :heading="__('auth.two_factor.heading')"
    :subheading="__('auth.two_factor.subheading')"
    :brandLine="__('auth.two_factor.brand_line')"
    :alpine="true"
>
    <div class="mb-6 flex size-11 items-center justify-center rounded-ui-md bg-ui-accent-soft text-ui-link">
        <x-ui.icon name="lock-closed" size="sm" />
    </div>

    <x-ui.alert-banner />

    <form
        method="POST"
        action="{{ route('two-factor.login') }}"
        id="two-factor-form"
        class="space-y-6"
        novalidate
        x-data="{ code: '', recoveryCode: '', submitting: false, mode: 'code' }"
        x-init="
            $nextTick(() => {
                $refs.codeInput.focus();
            });
            $watch('code', (value) => {
                if (mode === 'code' && value.length === 6 && ! submitting) {
                    submitting = true;
                    $nextTick(() => {
                        $el.submit();
                    });
                }
            });
        "
        @submit="submitting = true"
        x-cloak
    >
        @csrf

        <div>
            {{-- Labels swap with the mode. The strings ride in data-* attributes
                 rather than inside the x-text expression: escaping a quoted
                 literal into a double-quoted attribute is fragile, and this
                 version cannot break on an apostrophe. --}}
            <x-ui.label for="code" class="text-center">
                <span
                    data-code-label="{{ __('auth.two_factor.authentication_code') }}"
                    data-recovery-label="{{ __('auth.two_factor.recovery_code') }}"
                    x-text="mode === 'code' ? $el.dataset.codeLabel : $el.dataset.recoveryLabel"
                >{{ __('auth.two_factor.authentication_code') }}</span>
            </x-ui.label>

            {{-- The field that actually posts is the transparent overlay, so the
                 browser still offers one-time-code autofill on it. The boxes below
                 are drawn from its value. --}}
            <div class="relative mt-4" x-show="mode === 'code'">
                <input
                    id="code"
                    name="code"
                    type="text"
                    class="absolute inset-0 z-10 h-full w-full cursor-text opacity-0"
                    x-model="code"
                    x-ref="codeInput"
                    maxlength="6"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    autocomplete="one-time-code"
                    spellcheck="false"
                    :disabled="mode !== 'code'"
                    :readonly="submitting"
                    @input="code = code.replace(/\D/g, '').slice(0, 6)"
                />

                <div
                    class="flex justify-between gap-2"
                    :class="{ 'pointer-events-none opacity-50': submitting }"
                    aria-hidden="true"
                >
                    <template x-for="i in [0, 1, 2, 3, 4, 5]" :key="i">
                        <div
                            class="relative flex h-14 w-11 items-center justify-center rounded-ui-md border bg-ui-surface text-ui-xl font-semibold shadow-ui-xs transition-colors duration-120"
                            :class="{
                                'border-ui-line-strong': code.length !== i && ! submitting,
                                'border-ui-accent': code.length === i && ! submitting,
                                'border-ui-danger': {{ $errors->has('code') ? 'true' : 'false' }} && ! submitting,
                            }"
                        >
                            <span class="ui-num relative z-10" x-text="code[i] || ''"></span>

                            <span
                                class="ui-caret absolute h-6 w-px bg-ui-accent"
                                x-show="code.length === i && ! submitting"
                            ></span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Both fields stay in the DOM when the mode switches, and a hidden
                 field still submits — so the disabled binding is what keeps a
                 half-typed code out of the recovery request. This control is a
                 plain element for exactly that reason: `:disabled="expr"` on a
                 Blade tag compiles as PHP, and the look comes from the same
                 .ui-field class the component emits. --}}
            <div x-show="mode === 'recovery'" x-cloak>
                <input
                    type="text"
                    name="recovery_code"
                    id="recovery_code"
                    class="ui-field ui-field-code"
                    placeholder="XXXXX-XXXXXXXX"
                    autocomplete="one-time-code"
                    spellcheck="false"
                    x-model="recoveryCode"
                    x-ref="recoveryInput"
                    :disabled="mode !== 'recovery'"
                />
            </div>

            <p
                class="mt-4 flex items-center justify-center gap-2 text-ui-xs font-medium text-ui-link"
                x-show="submitting && mode === 'code'"
                x-transition
                role="status"
            >
                <svg class="size-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ __('auth.two_factor.verifying') }}
            </p>

            <button
                type="submit"
                class="ui-btn ui-btn-primary ui-btn-block mt-5"
                x-show="mode === 'recovery'"
                x-cloak
                :disabled="submitting"
            >{{ __('auth.two_factor.verify_recovery_code') }}</button>

            <x-ui.field-errors field="code" />
            <x-ui.field-errors field="recovery_code" />
        </div>

        <div class="text-center">
            <button
                type="button"
                class="rounded-ui-xs text-ui-sm text-ui-muted underline-offset-4 transition-colors hover:text-ui-text hover:underline focus:outline-none"
                @click="
                    mode = mode === 'code' ? 'recovery' : 'code';
                    $nextTick(() => {
                        (mode === 'code' ? $refs.codeInput : $refs.recoveryInput).focus();
                    });
                "
                data-recovery-cta="{{ __('auth.two_factor.use_recovery') }}"
                data-code-cta="{{ __('auth.two_factor.use_code') }}"
                x-text="mode === 'code' ? $el.dataset.recoveryCta : $el.dataset.codeCta"
            ></button>
        </div>
    </form>

    @if (session('link'))
        <p class="mt-5 text-ui-sm text-ui-muted">{{ session('link') }}</p>
    @endif
</x-layouts::auth>
