{{-- Guided recovery state for a rejected passkey sign-in. --}}
@props(['reason', 'title', 'message', 'cta' => null])

<div
    data-passkey-recovery="{{ $reason }}"
    role="alert"
    {{ $attributes->class('hidden rounded-2xl border border-[#E4E4E7] bg-[#F9FAFB] px-5 py-4') }}
>
    <p class="mb-1 text-sm font-bold text-[#18181B]">{{ $title }}</p>
    <p class="text-sm text-[#71717A]">{{ $message }}</p>

    @if ($cta)
        <button
            type="button"
            data-passkey-signin
            class="mt-3 rounded-xl border-2 border-[#E4E4E7] bg-white px-4 py-2 text-sm font-bold text-[#18181B] transition-colors hover:border-[#DC2626]/30 focus:ring-4 focus:ring-[#DC2626]/10 focus:outline-none"
        >{{ $cta }}</button>
    @endif
</div>
