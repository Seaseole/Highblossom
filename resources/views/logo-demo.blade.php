<x-layouts::site title="Logo Trust Badge Demo">
    <div class="py-24 px-6 lg:px-8 max-w-[1400px] mx-auto">
        <h1 class="text-4xl font-bold text-[#FAFAFA] font-headline mb-12">Logo Trust Badge Demo</h1>

        {{-- White Background Section --}}
        <section class="bg-white rounded-3xl p-12 mb-12">
            <h2 class="text-gray-900 font-bold mb-8 uppercase tracking-widest text-sm">Light Background</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 items-center text-gray-900">
                <x-logo-trust-badge business-name="VerifiedPro" variant="verified" font-size="text-2xl" :badge-size="18" />
                <x-logo-trust-badge business-name="TrustedCorp" variant="trusted" font-size="text-3xl" :badge-size="22" />
                <x-logo-trust-badge business-name="PremiumElite" variant="premium" font-size="text-4xl" :badge-size="26" />
            </div>
        </section>

        {{-- Dark Background Section --}}
        <section class="glass-card rounded-3xl p-12">
            <h2 class="text-[#FAFAFA] font-bold mb-8 uppercase tracking-widest text-sm">Dark Background</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 items-center">
                <x-logo-trust-badge business-name="VerifiedPro" variant="verified" font-size="text-2xl" :badge-size="18" />
                <x-logo-trust-badge business-name="TrustedCorp" variant="trusted" font-size="text-3xl" :badge-size="22" />
                <x-logo-trust-badge business-name="PremiumElite" variant="premium" font-size="text-4xl" :badge-size="26" />
            </div>
        </section>

        {{-- Scale Reference --}}
        <section class="mt-24">
            <h2 class="text-[#FAFAFA] font-bold mb-8 uppercase tracking-widest text-sm">Sizing Scales</h2>
            <div class="flex flex-col gap-12">
                <div class="flex items-center gap-8">
                    <span class="text-[#A1A1AA] w-24 text-xs font-mono">text-xl / 18px</span>
                    <x-logo-trust-badge business-name="Small Scale" font-size="text-xl" :badge-size="18" />
                </div>
                <div class="flex items-center gap-8">
                    <span class="text-[#A1A1AA] w-24 text-xs font-mono">text-3xl / 22px</span>
                    <x-logo-trust-badge business-name="Medium Scale" font-size="text-3xl" :badge-size="22" />
                </div>
                <div class="flex items-center gap-8">
                    <span class="text-[#A1A1AA] w-24 text-xs font-mono">text-5xl / 30px</span>
                    <x-logo-trust-badge business-name="Large Scale" font-size="text-5xl" :badge-size="30" />
                </div>
            </div>
        </section>
    </div>
</x-layouts::site>
