<?php

use App\Domains\Content\Models\ContactNumber;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';

    public function deleteNumber(int $id): void
    {
        ContactNumber::find($id)?->delete();
        $this->dispatch('notify', ['message' => 'Contact number deleted successfully!', 'type' => 'success']);
    }

    public function toggleActive(int $id): void
    {
        $number = ContactNumber::find($id);
        if ($number) {
            $number->update(['is_active' => !$number->is_active]);
            $this->dispatch('notify', ['message' => 'Contact number updated!', 'type' => 'success']);
        }
    }

    public function with(): array
    {
        return [
            'numbers' => ContactNumber::when($this->search, function ($query) {
                $query->where('label', 'like', '%' . $this->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $this->search . '%');
            })->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10),
        ];
    }
}; ?>

<flux:main>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-6">
            <flux:heading size="xl" level="1">{{ __('Contact Numbers') }}</flux:heading>
            <flux:button :href="route('admin.contact-numbers.create')" variant="primary" icon="plus">
                {{ __('Add Number') }}
            </flux:button>
        </div>

        <div class="mb-6 flex gap-4">
            <div class="flex-1">
                <flux:input wire:model.live="search" :placeholder="__('Search numbers...')" icon="magnifying-glass" />
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('Label') }}</flux:table.column>
                    <flux:table.column>{{ __('Phone Number') }}</flux:table.column>
                    <flux:table.column>{{ __('Type') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column align="end"></flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($numbers as $number)
                        <flux:table.row :key="$number->id">
                            <flux:table.cell>
                                <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $number->label }}</div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="text-sm text-zinc-900 dark:text-zinc-300">{{ $number->formatted_number }}</div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex gap-1">
                                    @if ($number->is_primary)
                                        <flux:badge color="blue" size="sm">{{ __('Primary') }}</flux:badge>
                                    @endif
                                    @if ($number->is_whatsapp)
                                        <flux:badge color="green" size="sm">{{ __('WhatsApp') }}</flux:badge>
                                    @endif
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:button variant="ghost" size="sm" wire:click="toggleActive({{ $number->id }})" 
                                    class="{{ $number->is_active ? 'text-green-600' : 'text-zinc-400' }}">
                                    {{ $number->is_active ? __('Active') : __('Inactive') }}
                                </flux:button>
                            </flux:table.cell>
                            <flux:table.cell align="end">
                                <div class="flex justify-end gap-1">
                                    <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.contact-numbers.edit', $number->id)" />
                                    <flux:button variant="ghost" size="sm" icon="trash" wire:click="deleteNumber({{ $number->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-600 hover:text-red-700" />
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center py-12 text-zinc-500">
                                {{ __('No contact numbers found.') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="mt-6">
            {{ $numbers->links() }}
        </div>
    </div>
</flux:main>
