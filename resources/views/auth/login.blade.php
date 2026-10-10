<x-auth-premium
    :title="__('auth.login.title')"
    :companyName="config('app.name')"
    :brandingSubtitle="__('auth.login.welcome_back')"
>
    <div class="mb-10">
        <h2 class="font-headline mb-2 text-3xl font-bold tracking-tight text-[#18181B]">
            {{ __('auth.login.heading') }}
        </h2>
        <p class="text-[#71717A]">{{ __('auth.login.subheading') }}</p>
    </div>

    <x-ui.alert-banner />

    @php
        $emailErrors = \App\Support\AuthNotification::fieldMessages('email');
        $passwordErrors = \App\Support\AuthNotification::fieldMessages('password');
    @endphp

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="animate-fade-in-up delay-200">
            <label
                for="email"
                class="mb-2 block px-1 text-xs font-bold tracking-widest text-[#71717A] uppercase"
            >{{ __('auth.login.email_label') }}</label>
            <input
                id="email"
                type="email"
                name="email"
                class="w-full rounded-2xl border bg-white/50 px-5 py-4 text-[#18181B] shadow-sm transition-all duration-300 placeholder-[#A1A1AA] focus:ring-2 focus:border-[#DC2626] focus:ring-[#DC2626]/20 focus:outline-none {{ $errors->has('email') ? 'border-[#DC2626]' : 'border-[#E4E4E7]' }}"
                placeholder="{{ __('auth.login.email_placeholder') }}"
                required
                autofocus
                autocomplete="username"
                value="{{ old('email') }}"
                @if ($emailErrors !== []) aria-describedby="email-error" aria-invalid="true" @endif
            />
            <x-ui.field-errors field="email" />
        </div>
        <div class="animate-fade-in-up delay-300">
            <div class="mb-2 flex items-center justify-between px-1">
                <label
                    for="password"
                    class="block text-xs font-bold tracking-widest text-[#71717A] uppercase"
                >{{ __('auth.login.password_label') }}</label>
                <a
                    href="{{ route('password.request') }}"
                    class="text-xs font-bold tracking-widest text-[#DC2626] uppercase transition-colors hover:text-[#B91C1C]"
                >
                    {{ __('auth.login.forgot_password') }}
                </a>
            </div>
            <div class="relative">
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="w-full rounded-2xl border bg-white/50 px-5 py-4 pr-14 text-[#18181B] shadow-sm transition-all duration-300 placeholder-[#A1A1AA] focus:ring-2 focus:border-[#DC2626] focus:ring-[#DC2626]/20 focus:outline-none {{ $errors->has('password') ? 'border-[#DC2626]' : 'border-[#E4E4E7]' }}"
                    placeholder="{{ __('auth.login.password_placeholder') }}"
                    required
                    autocomplete="current-password"
                    @if ($passwordErrors !== []) aria-describedby="password-error" aria-invalid="true" @endif
                />
                <button
                    type="button"
                    data-toggle-password="password"
                    aria-controls="password"
                    aria-expanded="false"
                    aria-label="{{ __('auth.login.show_password') }}"
                    data-show-label="{{ __('auth.login.show_password') }}"
                    data-hide-label="{{ __('auth.login.hide_password') }}"
                    class="absolute inset-y-0 right-0 flex items-center pr-5 text-[#A1A1AA] transition-colors hover:text-[#71717A] focus:text-[#71717A] focus:outline-none"
                >
                    <svg data-icon-eye class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg data-icon-eye-off class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.77 9.77 0 012.804-3.704M15.48 15.48l2.58 2.58M12 9a3 3 0 013 3m-3-3a3 3 0 00-3 3m0 0a3 3 0 013-3m0 0l-2.58-2.58M21 21l-9-9m0 0L3 3" />
                    </svg>
                </button>
            </div>
            <x-ui.field-errors field="password" />
        </div>

        <div class="animate-fade-in-up flex items-center justify-between px-1 delay-400">
            <div class="light-checkbox">
                <x-ui.checkbox name="remember" label="{{ __('auth.login.remember_me') }}" />
            </div>
        </div>

        <div class="animate-fade-in-up flex flex-col gap-4 pt-2 delay-500">
            <button
                type="submit"
                data-submit-pending-button
                class="group flex w-full items-center justify-center gap-2 rounded-2xl bg-[#DC2626] px-6 py-4 text-lg font-bold text-white shadow-xl shadow-[#DC2626]/20 transition-all duration-300 hover:bg-[#B91C1C] focus:ring-4 focus:ring-[#DC2626]/20 focus:outline-none active:scale-[0.98] disabled:cursor-wait disabled:opacity-60"
            >
                <span data-idle-state>{{ __('auth.login.sign_in_button') }}</span>
                <span data-pending-state class="hidden">
                    <span class="flex items-center gap-2">
                        <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('auth.login.signing_in') }}
                    </span>
                </span>
                <svg data-idle-state class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>

            <div class="relative flex items-center py-2">
                <div class="flex-grow border-t border-[#E4E4E7]"></div>
                <span class="mx-4 flex-shrink text-xs font-bold tracking-widest text-[#A1A1AA] uppercase">Or</span>
                <div class="flex-grow border-t border-[#E4E4E7]"></div>
            </div>

            {{-- @if (çonfig('features.passkey.enabled')) --}}
                <button
                type="button"
                id="passkey-signin-btn"
                data-passkey-signin
                class="group flex w-full items-center justify-center gap-3 rounded-2xl border-2 border-[#E4E4E7] bg-white px-6 py-4 text-lg font-bold text-[#18181B] transition-all duration-300 hover:border-[#DC2626]/30 hover:bg-[#F9FAFB] focus:ring-4 focus:ring-[#DC2626]/10 focus:outline-none active:scale-[0.98] disabled:cursor-wait disabled:opacity-60"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0 1 19.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 0 0 4.5 10.5a7.464 7.464 0 0 1-1.15 3.993m1.989 3.559A11.209 11.209 0 0 0 8.25 10.5a3.75 3.75 0 1 1 7.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 0 1-3.6 9.75m6.633-4.596a18.666 18.666 0 0 1-2.485 5.33" />
                </svg>
                <span
                    id="passkey-signin-label"
                    data-sign-in="{{ __('auth.login.passkey.button') }}"
                    data-waiting="{{ __('auth.login.passkey.waiting') }}"
                    data-unsupported="{{ __('auth.login.passkey.unsupported') }}"
                >{{ __('auth.login.passkey.button') }}</span>
            </button>
            {{-- @endif --}}

            <div
                id="passkey-error"
                role="alert"
                data-default="{{ __('auth.login.passkey.generic_error') }}"
                class="hidden rounded-2xl border border-[#DC2626]/30 bg-[#DC2626]/5 px-4 py-3 text-center text-sm font-medium text-[#B91C1C]"
            ></div>

            <x-passkey-recovery-panel
                reason="unrecognized_passkey"
                :title="__('auth.login.passkey.recovery.unrecognized_passkey.title')"
                :message="__('auth.login.passkey.recovery.unrecognized_passkey.message')"
                :cta="__('auth.login.passkey.recovery.unrecognized_passkey.try_another')"
            />
            <x-passkey-recovery-panel
                reason="expired_passkey_session"
                :title="__('auth.login.passkey.recovery.expired_passkey_session.title')"
                :message="__('auth.login.passkey.recovery.expired_passkey_session.message')"
                :cta="__('auth.login.passkey.button')"
            />
            <x-passkey-recovery-panel
                reason="passkey_verification_failed"
                :title="__('auth.login.passkey.recovery.passkey_verification_failed.title')"
                :message="__('auth.login.passkey.recovery.passkey_verification_failed.message')"
            />
            <x-passkey-recovery-panel
                reason="too_many_attempts"
                :title="__('auth.login.passkey.recovery.too_many_attempts.title')"
                :message="__('auth.login.passkey.recovery.too_many_attempts.message')"
            />
        </div>
    </form>

    @if (filter_var($settings['enable_registration'] ?? '0', FILTER_VALIDATE_BOOLEAN))
        <div class="animate-fade-in-up mt-10 text-center delay-500">
            <p class="font-medium text-[#71717A]">
                Don't have an account?
                <a
                    href="{{ route('register') }}"
                    class="font-bold text-[#DC2626] underline decoration-[#DC2626]/20 decoration-2 underline-offset-4 transition-colors hover:text-[#B91C1C] hover:decoration-[#DC2626]"
                >
                    Register
                </a>
            </p>
        </div>
    @endif
</x-auth-premium>
