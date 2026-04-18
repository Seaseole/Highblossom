<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Staff Absences')] class extends Component {
    //
}; ?>

<flux:main>
    <section class="w-full">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Staff Absences') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Manage staff availability and time off') }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>

        <div class="relative min-h-[400px] flex-1 rounded-xl border border-zinc-200 dark:border-zinc-700">
             <div class="p-8 text-center">
                <flux:heading>{{ __('No absences recorded') }}</flux:heading>
            </div>
        </div>
    </section>
</flux:main>
