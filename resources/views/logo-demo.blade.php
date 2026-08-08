<x-layouts::site title="Logo Trust Badge Demo">
    <div class="mx-auto max-w-[1400px] px-6 py-24 lg:px-8">
        <h1 class="font-headline mb-12 text-4xl font-bold text-[#FAFAFA]">Logo Trust Badge Demo</h1>

        {{-- White Background Section --}}
        <section class="mb-12 rounded-3xl bg-white p-12">
            <h2 class="mb-8 text-sm font-bold tracking-widest text-gray-900 uppercase">Light Background</h2>
            <div class="grid grid-cols-1 items-center gap-16 text-gray-900 md:grid-cols-2 lg:grid-cols-3">
                <x-logo-trust-badge
                    business-name="VerifiedPro"
                    variant="verified"
                    font-size="text-2xl"
                    :badge-size="18"
                />
                <x-logo-trust-badge
                    business-name="TrustedCorp"
                    variant="trusted"
                    font-size="text-3xl"
                    :badge-size="22"
                />
                <x-logo-trust-badge
                    business-name="PremiumElite"
                    variant="premium"
                    font-size="text-4xl"
                    :badge-size="26"
                />
            </div>
        </section>

        {{-- Dark Background Section --}}
        <section class="glass-card rounded-3xl p-12">
            <h2 class="mb-8 text-sm font-bold tracking-widest text-[#FAFAFA] uppercase">Dark Background</h2>
            <div class="grid grid-cols-1 items-center gap-16 md:grid-cols-2 lg:grid-cols-3">
                <x-logo-trust-badge
                    business-name="VerifiedPro"
                    variant="verified"
                    font-size="text-2xl"
                    :badge-size="18"
                />
                <x-logo-trust-badge
                    business-name="TrustedCorp"
                    variant="trusted"
                    font-size="text-3xl"
                    :badge-size="22"
                />
                <x-logo-trust-badge
                    business-name="PremiumElite"
                    variant="premium"
                    font-size="text-4xl"
                    :badge-size="26"
                />
            </div>
        </section>

        {{-- Scale Reference --}}
        <section class="mt-24">
            <h2 class="mb-8 text-sm font-bold tracking-widest text-[#FAFAFA] uppercase">Sizing Scales</h2>
            <div class="flex flex-col gap-12">
                <div class="flex items-center gap-8">
                    <span class="w-24 font-mono text-xs text-[#A1A1AA]">text-xl / 18px</span>
                    <x-logo-trust-badge business-name="Small Scale" font-size="text-xl" :badge-size="18" />
                </div>
                <div class="flex items-center gap-8">
                    <span class="w-24 font-mono text-xs text-[#A1A1AA]">text-3xl / 22px</span>
                    <x-logo-trust-badge business-name="Medium Scale" font-size="text-3xl" :badge-size="22" />
                </div>
                <div class="flex items-center gap-8">
                    <span class="w-24 font-mono text-xs text-[#A1A1AA]">text-5xl / 30px</span>
                    <x-logo-trust-badge business-name="Large Scale" font-size="text-5xl" :badge-size="30" />
                </div>
            </div>
        </section>
    </div>
</x-layouts::site>
