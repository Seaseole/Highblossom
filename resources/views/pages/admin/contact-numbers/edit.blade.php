<?php

use App\Domains\Content\Models\ContactNumber;
use Livewire\Component;

new class extends Component {
    public ?int $numberId = null;
    public string $label = '';
    public string $phoneNumber = '';
    public bool $isPrimary = false;
    public bool $isWhatsapp = false;
    public bool $isActive = true;
    public int $sortOrder = 0;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->numberId = $id;
            $number = ContactNumber::findOrFail($id);
            $this->label = $number->label;
            $this->phoneNumber = $number->phone_number;
            $this->isPrimary = $number->is_primary;
            $this->isWhatsapp = $number->is_whatsapp;
            $this->isActive = $number->is_active;
            $this->sortOrder = $number->sort_order;
        }
    }

    public function save(): void
    {
        $this->validate([
            'label' => 'required|string|max:255',
            'phoneNumber' => 'required|string|max:20',
            'isPrimary' => 'boolean',
            'isWhatsapp' => 'boolean',
            'isActive' => 'boolean',
            'sortOrder' => 'integer',
        ]);

        $data = [
            'label' => $this->label,
            'phone_number' => $this->phoneNumber,
            'is_primary' => $this->isPrimary,
            'is_whatsapp' => $this->isWhatsapp,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
        ];

        // If this is set as primary, remove primary from others
        if ($this->isPrimary && !$this->numberId) {
            ContactNumber::where('is_primary', true)->update(['is_primary' => false]);
        }

        if ($this->numberId) {
            ContactNumber::find($this->numberId)->update($data);
            $message = 'Contact number updated successfully!';
        } else {
            ContactNumber::create($data);
            $message = 'Contact number created successfully!';
        }

        $this->dispatch('notify', ['message' => $message, 'type' => 'success']);
        $this->redirect(route('admin.contact-numbers.index'));
    }
}; ?>

<flux:main>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <flux:heading size="xl" level="1" class="mb-6">{{ $numberId ? __('Edit Contact Number') : __('Add Contact Number') }}</flux:heading>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model="label" :label="__('Label')" placeholder="e.g., Sales, Emergency, Main" />

                <flux:input wire:model="phoneNumber" :label="__('Phone Number')" placeholder="+267 XX XXX XXX" />

                <flux:input type="number" wire:model="sortOrder" :label="__('Sort Order')" />

                <div class="flex flex-col gap-4 justify-center">
                    <flux:checkbox wire:model="isPrimary" :label="__('Primary Number')" />
                    <flux:checkbox wire:model="isWhatsapp" :label="__('WhatsApp')" />
                    <flux:checkbox wire:model="isActive" :label="__('Active')" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                <flux:button :href="route('admin.contact-numbers.index')" variant="ghost">{{ __('Cancel') }}</flux:button>
                <flux:button type="submit" variant="primary">
                    {{ $numberId ? __('Update Number') : __('Create Number') }}
                </flux:button>
            </div>
        </form>
    </div>
</flux:main>
