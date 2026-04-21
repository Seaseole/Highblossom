@props(['id' => 'modal', 'title' => 'Modal', 'maxWidth' => '2xl'])

<input type="checkbox" id="{{ $id }}-modal-toggle" class="hidden peer">

<!-- Backdrop with Premium Dark Blur -->
<div class="fixed inset-0 z-50 bg-[#0A0A0F]/95 backdrop-blur-3xl opacity-0 pointer-events-none peer-checked:opacity-100 peer-checked:pointer-events-auto transition-all duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]" aria-hidden="true"></div>

<!-- Modal Container -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 opacity-0 pointer-events-none peer-checked:opacity-100 peer-checked:pointer-events-auto transition-all duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]" style="min-height: 100dvh;">
    <div class="relative w-full max-w-{{ $maxWidth }} transform translate-y-12 scale-95 peer-checked:translate-y-0 peer-checked:scale-100 transition-all duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]">
        <!-- Outer Shell with Glass Morphism -->
        <div class="bg-white/5 border border-white/10 p-1.5 rounded-[1.5rem] shadow-2xl shadow-[#0A0A0F]/50">
            <!-- Inner Core with Dark Background -->
            <div class="bg-[#16161D] rounded-[calc(1.5rem-0.375rem)] overflow-hidden border border-white/5 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
                <!-- Header -->
                <div class="flex items-center justify-between px-8 py-6 border-b border-white/5">
                    <h3 class="text-2xl font-bold text-[#FAFAFA] font-headline">{{ $title }}</h3>
                    <label for="{{ $id }}-modal-toggle" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-[#A1A1AA] hover:bg-[#DC2626]/20 hover:text-[#DC2626] hover:border-[#DC2626]/30 transition-all duration-200 active:scale-[0.95] cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </label>
                </div>

                <!-- Body -->
                <div class="px-8 py-6">
                    {{ $slot }}
                </div>

                <!-- Footer (optional) -->
                @if(isset($footer))
                    <div class="px-8 py-4 bg-white/[0.02] border-t border-white/5">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
