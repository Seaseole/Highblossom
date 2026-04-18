<?php

declare(strict_types=1);

use App\Domains\Content\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Categories')] class extends Component
{
    public ?int $editingId = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $type = 'post';

    public function startCreating(): void
    {
        $this->editingId = 0;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->type = 'post';
    }

    public function edit(Category $category): void
    {
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->type = $category->type;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . ($this->editingId ?: 'NULL') . ',id',
            'description' => 'nullable|string',
            'type' => 'required|in:page,post',
        ]);

        if ($this->editingId === 0) {
            Category::create($validated);
            Flux::toast('Category created successfully.');
        } else {
            Category::find($this->editingId)?->update($validated);
            Flux::toast('Category updated successfully.');
        }

        $this->editingId = null;
    }

    public function delete(Category $category): void
    {
        if ($category->posts()->count() > 0 || $category->pages()->count() > 0) {
            Flux::toast('Cannot delete category with associated content.', 'error');
            return;
        }

        $category->delete();
        Flux::toast('Category deleted successfully.');
    }

    public function updatedName(): void
    {
        if (empty($this->slug)) {
            $this->slug = \Illuminate\Support\Str::slug($this->name);
        }
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('type')->orderBy('name')->get();
    }
}; ?>

<flux:main>
    <section class="w-full">
        <div class="relative mb-6 w-full">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="xl" level="1">{{ __('Categories') }}</flux:heading>
                    <flux:subheading size="lg" class="mb-6">{{ __('Manage post and page categories') }}</flux:subheading>
                </div>
                <flux:button
                    wire:click="startCreating"
                    variant="primary"
                    icon="plus"
                >
                    {{ __('New Category') }}
                </flux:button>
            </div>
            <flux:separator variant="subtle" />
        </div>

        <!-- Categories Table -->
        <div class="relative rounded-xl border border-zinc-200 dark:border-zinc-700">
            @if($this->categories->isEmpty())
                <div class="p-8 text-center">
                    <flux:heading>{{ __('No categories found') }}</flux:heading>
                    <flux:subheading>{{ __('Create your first category to organize content.') }}</flux:subheading>
                </div>
            @else
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">{{ __('Name') }}</th>
                            <th class="px-6 py-3">{{ __('Slug') }}</th>
                            <th class="px-6 py-3">{{ __('Type') }}</th>
                            <th class="px-6 py-3">{{ __('Content Count') }}</th>
                            <th class="px-6 py-3 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->categories as $category)
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $category->name }}
                                    </div>
                                    @if($category->description)
                                        <div class="text-xs text-gray-500 truncate max-w-xs">
                                            {{ $category->description }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $category->slug }}
                                </td>
                                <td class="px-6 py-4">
                                    <flux:badge size="sm" variant="{{ $category->type === 'post' ? 'primary' : 'secondary' }}">
                                        {{ ucfirst($category->type) }}
                                    </flux:badge>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $category->type === 'post' ? $category->posts_count : $category->pages_count }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button
                                            wire:click="edit({{ $category->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil"
                                        />
                                        <flux:button
                                            wire:click="delete({{ $category->id }})"
                                            wire:confirm="{{ __('Are you sure?') }}"
                                            size="sm"
                                            variant="ghost"
                                            icon="trash"
                                        />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </section>

    <!-- Edit Modal -->
    <flux:modal wire:model="editingId" title="{{ $editingId === 0 ? __('New Category') : __('Edit Category') }}">
        <div class="space-y-4">
            <flux:input wire:model="name" label="{{ __('Name') }}" required />
            <flux:input wire:model="slug" label="{{ __('Slug') }}" required />
            <flux:textarea wire:model="description" label="{{ __('Description') }}" rows="3" />
            <flux:select wire:model="type" label="{{ __('Type') }}">
                <option value="post">{{ __('Blog Post') }}</option>
                <option value="page">{{ __('Page') }}</option>
            </flux:select>
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
