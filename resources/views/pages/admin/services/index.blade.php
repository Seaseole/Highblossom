<?php

use App\Domains\Content\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';

    public function deleteService(int $id): void
    {
        Service::find($id)?->delete();
        $this->dispatch('notify', ['message' => 'Service deleted successfully!', 'type' => 'success']);
    }

    public function toggleActive(int $id): void
    {
        $service = Service::find($id);
        if ($service) {
            $service->update(['is_active' => !$service->is_active]);
            $this->dispatch('notify', ['message' => 'Service updated!', 'type' => 'success']);
        }
    }

    public function with(): array
    {
        return [
            'services' => Service::when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10),
        ];
    }
}; ?>

<flux:main>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-6">
            <flux:heading size="xl" level="1">{{ __('Services') }}</flux:heading>
            <flux:button :href="route('admin.services.create')" variant="primary" icon="plus">
                {{ __('Add Service') }}
            </flux:button>
        </div>

        <div class="mb-6 flex gap-4">
            <div class="flex-1">
                <flux:input wire:model.live="search" :placeholder="__('Search services...')" icon="magnifying-glass" />
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('Icon') }}</flux:table.column>
                    <flux:table.column>{{ __('Service') }}</flux:table.column>
                    <flux:table.column>{{ __('Description') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column align="end"></flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($services as $service)
                        <flux:table.row :key="$service->id">
                            <flux:table.cell>
                                <span class="material-symbols-outlined text-primary">{{ $service->icon }}</span>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $service->title }}</div>
                                <div class="text-xs text-zinc-500">{{ $service->slug }}</div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="text-sm text-zinc-500 truncate max-w-xs">{{ $service->short_description }}</div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:button variant="ghost" size="sm" wire:click="toggleActive({{ $service->id }})" 
                                    class="{{ $service->is_active ? 'text-green-600' : 'text-zinc-400' }}">
                                    {{ $service->is_active ? __('Active') : __('Inactive') }}
                                </flux:button>
                            </flux:table.cell>
                            <flux:table.cell align="end">
                                <div class="flex justify-end gap-1">
                                    <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.services.edit', $service->id)" />
                                    <flux:button variant="ghost" size="sm" icon="trash" wire:click="deleteService({{ $service->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-600 hover:text-red-700" />
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center py-12 text-zinc-500">
                                {{ __('No services found.') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="mt-6">
            {{ $services->links() }}
        </div>
    </div>
</flux:main>
