<?php

use App\Domains\Content\Models\CompanySetting;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;

new class extends Component {
    public string $smtpHost = '';
    public string $smtpPort = '';
    public string $smtpUsername = '';
    public string $smtpPassword = '';
    public string $smtpEncryption = '';

    public function mount(): void
    {
        $this->smtpHost = CompanySetting::get('smtp_host', '');
        $this->smtpPort = (string) CompanySetting::get('smtp_port', '587');
        $this->smtpUsername = CompanySetting::get('smtp_username', '');
        $this->smtpPassword = CompanySetting::get('smtp_password', '');
        $this->smtpEncryption = CompanySetting::get('smtp_encryption', 'tls');
    }

    public function save(): void
    {
        $this->validate([
            'smtpHost' => 'nullable|string|max:255',
            'smtpPort' => 'nullable|numeric',
            'smtpUsername' => 'nullable|string|max:255',
            'smtpPassword' => 'nullable|string|max:255',
            'smtpEncryption' => 'nullable|in:tls,ssl,',
        ]);

        CompanySetting::set('smtp_host', $this->smtpHost, 'text');
        CompanySetting::set('smtp_port', (int) $this->smtpPort, 'number');
        CompanySetting::set('smtp_username', $this->smtpUsername, 'text');
        CompanySetting::set('smtp_password', $this->smtpPassword, 'text');
        CompanySetting::set('smtp_encryption', $this->smtpEncryption, 'text');

        $this->dispatch('notify', ['message' => 'SMTP settings saved successfully!', 'type' => 'success']);
    }

    public function testConnection(): void
    {
        $this->save();

        try {
            config(['mail.mailers.smtp.host' => $this->smtpHost]);
            config(['mail.mailers.smtp.port' => $this->smtpPort]);
            config(['mail.mailers.smtp.username' => $this->smtpUsername]);
            config(['mail.mailers.smtp.password' => $this->smtpPassword]);
            config(['mail.mailers.smtp.encryption' => $this->smtpEncryption]);
            config(['mail.from.address' => $this->smtpUsername]);

            Mail::raw('Test email from Highblossom', function ($message) {
                $message->to($this->smtpUsername)
                    ->subject('SMTP Test');
            });

            $this->dispatch('notify', ['message' => 'Test email sent successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'Failed to send test email: ' . $e->getMessage(), 'type' => 'error']);
        }
    }
}; ?>

<flux:main>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <flux:heading size="xl" level="1" class="mb-6">{{ __('SMTP Settings') }}</flux:heading>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model="smtpHost" :label="__('SMTP Host')" placeholder="smtp.gmail.com" />

                <flux:input type="number" wire:model="smtpPort" :label="__('SMTP Port')" />

                <flux:input wire:model="smtpUsername" :label="__('SMTP Username')" />

                <flux:input type="password" wire:model="smtpPassword" :label="__('SMTP Password')" viewable />

                <flux:select wire:model="smtpEncryption" :label="__('Encryption')">
                    <flux:select.option value="">{{ __('None') }}</flux:select.option>
                    <flux:select.option value="tls">{{ __('TLS') }}</flux:select.option>
                    <flux:select.option value="ssl">{{ __('SSL') }}</flux:select.option>
                </flux:select>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                <flux:button type="button" wire:click="testConnection" variant="ghost">
                    {{ __('Test Connection') }}
                </flux:button>
                <flux:button type="submit" variant="primary">
                    {{ __('Save Settings') }}
                </flux:button>
            </div>
        </form>
    </div>
</flux:main>
