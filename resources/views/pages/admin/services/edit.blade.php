<?php

use App\Domains\Content\Models\Service;
use Livewire\Component;

new class extends Component {
    public ?int $serviceId = null;
    public string $title = '';
    public string $slug = '';
    public string $icon = '';
    public string $shortDescription = '';
    public string $fullDescription = '';
    public array $features = [];
    public string $newFeature = '';
    public bool $isActive = true;
    public int $sortOrder = 0;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->serviceId = $id;
            $service = Service::findOrFail($id);
            $this->title = $service->title;
            $this->slug = $service->slug;
            $this->icon = $service->icon;
            $this->shortDescription = $service->short_description;
            $this->fullDescription = $service->full_description ?? '';
            $this->features = $service->features ?? [];
            $this->isActive = $service->is_active;
            $this->sortOrder = $service->sort_order;
        }
    }

    public function updatedTitle(): void
    {
        if (!$this->serviceId) {
            $this->slug = \Illuminate\Support\Str::slug($this->title);
        }
    }

    public function addFeature(): void
    {
        if ($this->newFeature) {
            $this->features[] = $this->newFeature;
            $this->newFeature = '';
        }
    }

    public function removeFeature(int $index): void
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,' . $this->serviceId,
            'icon' => 'required|string|max:255',
            'shortDescription' => 'required|string',
            'fullDescription' => 'nullable|string',
            'features' => 'nullable|array',
            'isActive' => 'boolean',
            'sortOrder' => 'integer',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'short_description' => $this->shortDescription,
            'full_description' => $this->fullDescription,
            'features' => $this->features,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
        ];

        if ($this->serviceId) {
            Service::find($this->serviceId)->update($data);
            $message = 'Service updated successfully!';
        } else {
            Service::create($data);
            $message = 'Service created successfully!';
        }

        $this->dispatch('notify', ['message' => $message, 'type' => 'success']);
        $this->redirect(route('admin.services.index'));
    }
}; ?>

<flux:main>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <flux:heading size="xl" level="1" class="mb-6">{{ $serviceId ? __('Edit Service') : __('Add Service') }}</flux:heading>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model.live="title" :label="__('Title')" />

                <flux:input wire:model="slug" :label="__('Slug')" />

                <flux:input wire:model="icon" :label="__('Material Icon Name')" placeholder="e.g., directions_car" />

                <flux:input type="number" wire:model="sortOrder" :label="__('Sort Order')" />

                <div class="md:col-span-2">
                    <flux:input wire:model="shortDescription" :label="__('Short Description')" />
                </div>

                <div class="md:col-span-2">
                    <flux:textarea wire:model="fullDescription" :label="__('Full Description')" rows="4" />
                </div>

                <div class="md:col-span-2">
                    <flux:label>{{ __('Features') }}</flux:label>
                    <div class="flex gap-2 mb-4">
                        <flux:input wire:model="newFeature" :placeholder="__('Add a feature...')" class="flex-1" wire:keydown.enter.prevent="addFeature" />
                        <flux:button type="button" wire:click="addFeature">{{ __('Add') }}</flux:button>
                    </div>
                    
                    <div class="space-y-2">
                        @foreach ($features as $index => $feature)
                            <div class="flex items-center gap-2 bg-zinc-50 dark:bg-zinc-900 p-2 rounded-lg border border-zinc-200 dark:border-zinc-800">
                                <span class="flex-1 text-sm">{{ $feature }}</span>
                                <flux:button variant="ghost" size="sm" icon="trash" wire:click="removeFeature({{ $index }})" class="text-red-600 hover:text-red-700" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center">
                    <flux:checkbox wire:model="isActive" :label="__('Active')" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                <flux:button :href="route('admin.services.index')" variant="ghost">{{ __('Cancel') }}</flux:button>
                <flux:button type="submit" variant="primary">
                    {{ $serviceId ? __('Update Service') : __('Create Service') }}
                </flux:button>
            </div>
        </form>
    </div>
</flux:main>
