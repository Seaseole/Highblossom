<?php

use App\Domains\Content\Models\CompanySetting;
use App\Domains\Content\Models\ContactNumber;
use Livewire\Component;

new class extends Component {
    public string $companyName = '';
    public string $logoText = '';
    public string $primaryPhone = '';
    public string $whatsappNumber = '';
    public string $primaryEmail = '';
    public string $address = '';

    public function mount(): void
    {
        $this->companyName = CompanySetting::get('company_name', 'Highblossom PTY LTD');
        $this->logoText = CompanySetting::get('logo_text', 'Highblossom PTY LTD');
        $this->primaryEmail = CompanySetting::get('primary_email', 'info@highblossom.co.bw');
        $this->address = CompanySetting::get('address', 'Plot 123, Main Road, Broadhurst, Gaborone, Botswana');

        // Read phone numbers from ContactNumber model (single source of truth)
        $primaryContact = ContactNumber::active()->primary()->first();
        $whatsappContact = ContactNumber::active()->whatsapp()->first();
        $this->primaryPhone = $primaryContact?->phone_number ?? '+26712345678';
        $this->whatsappNumber = $whatsappContact?->phone_number ?? '+26712345678';
    }

    public function save(): void
    {
        $this->validate([
            'companyName' => 'required|string|max:255',
            'logoText' => 'required|string|max:255',
            'primaryPhone' => 'required|string|max:20',
            'whatsappNumber' => 'required|string|max:20',
            'primaryEmail' => 'required|email|max:255',
            'address' => 'required|string|max:500',
        ]);

        // Update company settings
        CompanySetting::set('company_name', $this->companyName, 'text');
        CompanySetting::set('logo_text', $this->logoText, 'text');
        CompanySetting::set('primary_email', $this->primaryEmail, 'text');
        CompanySetting::set('address', $this->address, 'text');

        // Update contact numbers (single source of truth)
        ContactNumber::where('is_primary', true)->update(['phone_number' => $this->primaryPhone]);
        ContactNumber::where('is_whatsapp', true)->update(['phone_number' => $this->whatsappNumber]);

        $this->dispatch('notify', ['message' => 'Company settings saved successfully!', 'type' => 'success']);
    }
}; ?>

<flux:main>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <flux:heading size="xl" level="1" class="mb-6">{{ __('Company Settings') }}</flux:heading>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model="companyName" :label="__('Company Name')" />

                <flux:input wire:model="logoText" :label="__('Logo Text')" />

                <flux:input wire:model="primaryPhone" :label="__('Primary Phone')" />

                <flux:input wire:model="whatsappNumber" :label="__('WhatsApp Number')" />

                <div class="md:col-span-2">
                    <flux:input type="email" wire:model="primaryEmail" :label="__('Primary Email')" />
                </div>

                <div class="md:col-span-2">
                    <flux:textarea wire:model="address" :label="__('Address')" rows="3" />
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-zinc-200 dark:border-zinc-700">
                <flux:button type="submit" variant="primary">
                    {{ __('Save Settings') }}
                </flux:button>
            </div>
        </form>
    </div>
</flux:main>
