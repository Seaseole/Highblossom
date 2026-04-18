<?php

use App\Domains\Content\Models\Testimonial;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';

    public function deleteTestimonial(int $id): void
    {
        Testimonial::find($id)?->delete();
        $this->dispatch('notify', ['message' => 'Testimonial deleted successfully!', 'type' => 'success']);
    }

    public function toggleActive(int $id): void
    {
        $testimonial = Testimonial::find($id);
        if ($testimonial) {
            $testimonial->update(['is_active' => !$testimonial->is_active]);
            $this->dispatch('notify', ['message' => 'Testimonial updated!', 'type' => 'success']);
        }
    }

    public function with(): array
    {
        return [
            'testimonials' => Testimonial::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('company', 'like', '%' . $this->search . '%');
            })->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10),
        ];
    }
}; ?>

<flux:main>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-6">
            <flux:heading size="xl" level="1">{{ __('Testimonials') }}</flux:heading>
            <flux:button :href="route('admin.testimonials.create')" variant="primary" icon="plus">
                {{ __('Add Testimonial') }}
            </flux:button>
        </div>

        <div class="mb-6 flex gap-4">
            <div class="flex-1">
                <flux:input wire:model.live="search" :placeholder="__('Search testimonials...')" icon="magnifying-glass" />
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('Client') }}</flux:table.column>
                    <flux:table.column>{{ __('Company') }}</flux:table.column>
                    <flux:table.column>{{ __('Rating') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column align="end"></flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($testimonials as $testimonial)
                        <flux:table.row :key="$testimonial->id">
                            <flux:table.cell>
                                <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $testimonial->name }}</div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="text-sm text-zinc-500">{{ $testimonial->company }}</div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <flux:icon name="star" variant="{{ $i <= $testimonial->rating ? 'solid' : 'outline' }}" class="w-4 h-4" />
                                    @endfor
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:button variant="ghost" size="sm" wire:click="toggleActive({{ $testimonial->id }})" 
                                    class="{{ $testimonial->is_active ? 'text-green-600' : 'text-zinc-400' }}">
                                    {{ $testimonial->is_active ? __('Active') : __('Inactive') }}
                                </flux:button>
                            </flux:table.cell>
                            <flux:table.cell align="end">
                                <div class="flex justify-end gap-1">
                                    <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.testimonials.edit', $testimonial->id)" />
                                    <flux:button variant="ghost" size="sm" icon="trash" wire:click="deleteTestimonial({{ $testimonial->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-600 hover:text-red-700" />
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center py-12 text-zinc-500">
                                {{ __('No testimonials found.') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="mt-6">
            {{ $testimonials->links() }}
        </div>
    </div>
</flux:main>
