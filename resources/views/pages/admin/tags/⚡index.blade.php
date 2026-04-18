<?php

declare(strict_types=1);

use App\Domains\Content\Models\Tag;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Tags')] class extends Component
{
    public ?int $editingId = null;
    public string $name = '';
    public string $slug = '';

    public function startCreating(): void
    {
        $this->editingId = 0;
        $this->name = '';
        $this->slug = '';
    }

    public function edit(Tag $tag): void
    {
        $this->editingId = $tag->id;
        $this->name = $tag->name;
        $this->slug = $tag->slug;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,' . ($this->editingId ?: 'NULL') . ',id',
        ]);

        if ($this->editingId === 0) {
            Tag::create($validated);
            Flux::toast('Tag created successfully.');
        } else {
            Tag::find($this->editingId)?->update($validated);
            Flux::toast('Tag updated successfully.');
        }

        $this->editingId = null;
    }

    public function delete(Tag $tag): void
    {
        $tag->delete();
        Flux::toast('Tag deleted successfully.');
    }

    public function updatedName(): void
    {
        if (empty($this->slug) || $this->editingId === 0) {
            $this->slug = \Illuminate\Support\Str::slug($this->name);
        }
    }

    public function getTagsProperty()
    {
        return Tag::orderBy('name')->get();
    }
}; ?>

<flux:main>
    <section class="w-full">
        <div class="relative mb-6 w-full">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="xl" level="1">{{ __('Tags') }}</flux:heading>
                    <flux:subheading size="lg" class="mb-6">{{ __('Manage content tags') }}</flux:subheading>
                </div>
                <flux:button
                    wire:click="startCreating"
                    variant="primary"
                    icon="plus"
                >
                    {{ __('New Tag') }}
                </flux:button>
            </div>
            <flux:separator variant="subtle" />
        </div>

        <!-- Tags Cloud -->
        <div class="relative rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
            @if($this->tags->isEmpty())
                <div class="text-center">
                    <flux:heading>{{ __('No tags found') }}</flux:heading>
                    <flux:subheading>{{ __('Create your first tag to organize content.') }}</flux:subheading>
                </div>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($this->tags as $tag)
                        <div class="group flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-full">
                            <span class="text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                            <div class="hidden group-hover:flex items-center gap-1">
                                <button
                                    wire:click="edit({{ $tag->id }})"
                                    class="text-gray-500 hover:text-indigo-600"
                                    title="{{ __('Edit') }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </button>
                                <button
                                    wire:click="delete({{ $tag->id }})"
                                    wire:confirm="{{ __('Delete this tag?') }}"
                                    class="text-gray-500 hover:text-red-600"
                                    title="{{ __('Delete') }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Edit Modal -->
    <flux:modal wire:model="editingId" title="{{ $editingId === 0 ? __('New Tag') : __('Edit Tag') }}">
        <div class="space-y-4">
            <flux:input wire:model="name" label="{{ __('Name') }}" required />
            <flux:input wire:model="slug" label="{{ __('Slug') }}" required />
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <flux:button wire:click="$set('editingId', null)" variant="ghost">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button wire:click="save" variant="primary">
                {{ __('Save') }}
            </flux:button>
        </div>
    </flux:modal>
</flux:main>
