<?php

declare(strict_types=1);

use App\Domains\Content\Actions\Posts\CreateRevision;
use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\PostRevision;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Post Revisions')] class extends Component
{
    public int $postId;
    public ?Post $post = null;
    public ?int $comparingRevisionId = null;

    public function mount(int $id): void
    {
        $this->postId = $id;
        $this->post = Post::with(['revisions.user', 'author'])->findOrFail($id);
    }

    public function getRevisionsProperty()
    {
        return $this->post->revisions()->with('user')->latest()->paginate(20);
    }

    public function compare(int $revisionId): void
    {
        $this->comparingRevisionId = $revisionId;
    }

    public function closeCompare(): void
    {
        $this->comparingRevisionId = null;
    }

    public function restore(int $revisionId): void
    {
        $revision = $this->post->revisions()->findOrFail($revisionId);

        // Create a revision of current state before restoring
        $createRevision = new CreateRevision();
        $createRevision->execute(
            $this->post,
            auth()->user(),
            'Auto-saved before restoring to previous revision #' . $revisionId
        );

        // Restore the selected revision
        $revision->restore();

        Flux::toast('Revision restored successfully.');
        $this->redirect(route('admin.blog.edit', $this->post), navigate: true);
    }

    public function deleteRevision(int $revisionId): void
    {
        $this->post->revisions()->findOrFail($revisionId)->delete();
        Flux::toast('Revision deleted.');
    }

    public function getCurrentBlocks(): array
    {
        return $this->post->contentBlocks
            ->sortBy('sort_order')
            ->map(fn ($block) => [
                'type' => $block->type,
                'content' => $block->content,
            ])
            ->toArray();
    }

    public function getRevisionBlocks(int $revisionId): array
    {
        $revision = $this->post->revisions()->find($revisionId);
        return $revision?->content_blocks ?? [];
    }
}; ?>

<flux:main>
    <section class="w-full">
        <div class="relative mb-6 w-full">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <a href="{{ route('admin.blog.edit', $post) }}" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <flux:heading size="xl" level="1">{{ __('Post Revisions') }}</flux:heading>
                    </div>
                    <flux:subheading size="lg">{{ $post->title }}</flux:subheading>
                </div>
                <div class="flex items-center gap-2">
                    <flux:badge size="sm" variant="primary">
                        {{ $this->revisions->total() }} {{ __('revisions') }}
                    </flux:badge>
                </div>
            </div>
            <flux:separator variant="subtle" />
        </div>

        <!-- Comparison View -->
        @if($comparingRevisionId)
            <div class="mb-6 bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold">{{ __('Comparing Revisions') }}</h3>
                    <flux:button wire:click="closeCompare" variant="ghost" size="sm">
                        {{ __('Close') }}
                    </flux:button>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Current Version -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Current Version') }}
                            <span class="text-xs text-gray-500 ml-2">{{ $post->updated_at?->format('M j, Y g:i a') }}</span>
                        </h4>
                        <div class="bg-white dark:bg-gray-900 rounded-lg p-4 space-y-2">
                            @foreach($this->getCurrentBlocks() as $block)
                                <div class="p-2 bg-gray-50 dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                                    <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400">{{ ucfirst($block['type']) }}</span>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                        {{ json_encode($block['content'], JSON_PRETTY_PRINT) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Revision -->
                    <div>
                        @php($revision = $post->revisions()->find($comparingRevisionId))
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Revision') }} #{{ $comparingRevisionId }}
                            @if($revision)
                                <span class="text-xs text-gray-500 ml-2">{{ $revision->created_at?->format('M j, Y g:i a') }}</span>
                            @endif
                        </h4>
                        <div class="bg-white dark:bg-gray-900 rounded-lg p-4 space-y-2">
                            @foreach($this->getRevisionBlocks($comparingRevisionId) as $block)
                                <div class="p-2 bg-gray-50 dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 {{ !$this->blockExistsInCurrent($block) ? 'border-l-4 border-l-yellow-400' : '' }}">
                                    <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400">{{ ucfirst($block['type']) }}</span>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                        {{ json_encode($block['content'], JSON_PRETTY_PRINT) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Revisions List -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            @if($this->revisions->isEmpty())
                <div class="p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('No revisions yet') }}</h3>
                    <p class="text-gray-500 dark:text-gray-400">{{ __('Revisions are created automatically when you save changes.') }}</p>
                </div>
            @else
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">{{ __('Revision') }}</th>
                            <th class="px-6 py-3">{{ __('Date') }}</th>
                            <th class="px-6 py-3">{{ __('Author') }}</th>
                            <th class="px-6 py-3">{{ __('Note') }}</th>
                            <th class="px-6 py-3 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->revisions as $revision)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4">
                                    <span class="font-medium">#{{ $revision->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>{{ $revision->created_at->format('M j, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $revision->created_at->format('g:i a') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $revision->user?->name ?? __('Unknown') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-600 dark:text-gray-400">{{ $revision->revision_note ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button
                                            wire:click="compare({{ $revision->id }})"
                                            size="sm"
                                            variant="ghost"
                                        >
                                            {{ __('Compare') }}
                                        </flux:button>
                                        <flux:button
                                            wire:click="restore({{ $revision->id }})"
                                            wire:confirm="{{ __('Restore this revision? Current content will be saved as a new revision.') }}"
                                            size="sm"
                                            variant="primary"
                                        >
                                            {{ __('Restore') }}
                                        </flux:button>
                                        <flux:button
                                            wire:click="deleteRevision({{ $revision->id }})"
                                            wire:confirm="{{ __('Delete this revision?') }}"
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

                <div class="p-4">
                    {{ $this->revisions->links() }}
                </div>
            @endif
        </div>
    </section>
</flux:main>
