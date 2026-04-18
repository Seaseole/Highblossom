<?php

use App\Domains\Content\Models\Testimonial;
use Livewire\Component;

new class extends Component {
    public ?int $testimonialId = null;
    public string $name = '';
    public string $company = '';
    public int $rating = 5;
    public string $comment = '';
    public bool $isActive = true;
    public int $sortOrder = 0;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->testimonialId = $id;
            $testimonial = Testimonial::findOrFail($id);
            $this->name = $testimonial->name;
            $this->company = $testimonial->company;
            $this->rating = $testimonial->rating;
            $this->comment = $testimonial->comment;
            $this->isActive = $testimonial->is_active;
            $this->sortOrder = $testimonial->sort_order;
        }
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'isActive' => 'boolean',
            'sortOrder' => 'integer',
        ]);

        $data = [
            'name' => $this->name,
            'company' => $this->company,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
        ];

        if ($this->testimonialId) {
            Testimonial::find($this->testimonialId)->update($data);
            $message = 'Testimonial updated successfully!';
        } else {
            Testimonial::create($data);
            $message = 'Testimonial created successfully!';
        }

        $this->dispatch('notify', ['message' => $message, 'type' => 'success']);
        $this->redirect(route('admin.testimonials.index'));
    }
}; ?>

<flux:main>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <flux:heading size="xl" level="1" class="mb-6">{{ $testimonialId ? __('Edit Testimonial') : __('Add Testimonial') }}</flux:heading>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model="name" :label="__('Name')" />

                <flux:input wire:model="company" :label="__('Company / Role')" />

                <flux:input type="number" wire:model="rating" min="1" max="5" :label="__('Rating (1-5)')" />

                <flux:input type="number" wire:model="sortOrder" :label="__('Sort Order')" />

                <div class="md:col-span-2">
                    <flux:textarea wire:model="comment" :label="__('Comment')" rows="4" />
                </div>

                <div class="flex items-center">
                    <flux:checkbox wire:model="isActive" :label="__('Active')" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                <flux:button :href="route('admin.testimonials.index')" variant="ghost">{{ __('Cancel') }}</flux:button>
                <flux:button type="submit" variant="primary">
                    {{ $testimonialId ? __('Update Testimonial') : __('Create Testimonial') }}
                </flux:button>
            </div>
        </form>
    </div>
</flux:main>
