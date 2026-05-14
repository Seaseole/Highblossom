<div class="mt-4">
    @include('passkeys::components.partials.authenticateScript')

    <form id="passkey-login-form" method="POST" action="{{ route('passkeys.login') }}">
        @csrf
    </form>

    @if($message = session()->get('authenticatePasskey::message'))
        <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 animate-fade-in-up">
            <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <p class="text-sm text-red-700 font-medium leading-tight">{{ $message }}</p>
        </div>
    @endif

    <div onclick="authenticateWithPasskey()">
        @if ($slot->isEmpty())
            <button type="button" class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-white border-2 border-[#E4E4E7] rounded-2xl text-[#18181B] font-bold hover:bg-[#F9FAFB] hover:border-[#D4D4D8] transition-all duration-300 active:scale-[0.98] shadow-sm">
                <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <span>{{ __('passkeys::passkeys.authenticate_using_passkey') }}</span>
            </button>
        @else
            {{ $slot }}
        @endif
    </div>
</div>
