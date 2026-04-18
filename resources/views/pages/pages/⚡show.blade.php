<?php

use App\Domains\Content\Models\Page;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Title;
use Livewire\Component;

new class extends Component {
    public Page $page;

    public function mount(Page $page)
    {
        $this->page = Cache::flexible("page_{$page->slug}", [600, 1200], function () use ($page) {
            return $page->load(['contentBlocks' => fn($q) => $q->orderBy('sort_order')]);
        });
    }
}; ?>

<x-layouts::site :title="$page->title">
    <flux:main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <flux:heading size="xl" level="1" class="mb-4">{{ $page->title }}</flux:heading>
                    
                    <div class="space-y-8">
                        @foreach($page->contentBlocks as $block)
                            <div class="content-block" data-type="{{ $block->type }}">
                                @includeFirst(['components.blocks.' . $block->type, 'components.blocks.default'], ['content' => $block->content])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </flux:main>
</x-layouts::site>
