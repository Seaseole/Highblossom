<?php

use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public string $name = 'content';

    public array $blocks = [];

    public array $availableBlockTypes = [
        'paragraph' => 'Paragraph',
        'heading' => 'Heading',
        'image' => 'Image',
        'quote' => 'Quote',
        'code' => 'Code',
        'list' => 'List',
        'cta' => 'Call to Action',
        'video' => 'Video',
    ];

    public function mount(string $name = 'content', mixed $value = null): void
    {
        $this->name = $name;

        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        $this->blocks = $value ?? [];
    }

    public function addBlock(string $type): void
    {
        $block = [
            'id' => uniqid(),
            'type' => $type,
            'attributes' => $this->getDefaultAttributes($type),
        ];

        $this->blocks[] = $block;
        $this->dispatch('blocks-updated', blocks: $this->blocks);
    }

    public function removeBlock(string $id): void
    {
        $this->blocks = array_filter($this->blocks, fn ($block) => $block['id'] !== $id);
        $this->blocks = array_values($this->blocks);
        $this->dispatch('blocks-updated', blocks: $this->blocks);
    }

    public function moveBlock(int $fromIndex, int $toIndex): void
    {
        if ($toIndex < 0 || $toIndex >= count($this->blocks)) {
            return;
        }

        $block = $this->blocks[$fromIndex];
        unset($this->blocks[$fromIndex]);
        array_splice($this->blocks, $toIndex, 0, [$block]);
        $this->blocks = array_values($this->blocks);
        $this->dispatch('blocks-updated', blocks: $this->blocks);
    }

    public function updateBlockAttribute(string $id, string $key, mixed $value): void
    {
        foreach ($this->blocks as &$block) {
            if ($block['id'] === $id) {
                $block['attributes'][$key] = $value;
                break;
            }
        }
        $this->dispatch('blocks-updated', blocks: $this->blocks);
    }

    public function addListItem(string $id): void
    {
        foreach ($this->blocks as &$block) {
            if ($block['id'] === $id) {
                $block['attributes']['items'][] = '';
                break;
            }
        }
        $this->dispatch('blocks-updated', blocks: $this->blocks);
    }

    public function removeListItem(string $id, int $itemIndex): void
    {
        foreach ($this->blocks as &$block) {
            if ($block['id'] === $id) {
                unset($block['attributes']['items'][$itemIndex]);
                $block['attributes']['items'] = array_values($block['attributes']['items']);
                break;
            }
        }
        $this->dispatch('blocks-updated', blocks: $this->blocks);
    }

    protected function getDefaultAttributes(string $type): array
    {
        return match ($type) {
            'paragraph' => ['content' => '', 'class' => ''],
            'heading' => ['content' => '', 'level' => 2, 'class' => ''],
            'image' => ['src' => '', 'alt' => '', 'caption' => '', 'class' => ''],
            'quote' => ['content' => '', 'author' => '', 'cite' => '', 'class' => ''],
            'code' => ['content' => '', 'language' => 'javascript', 'class' => ''],
            'list' => ['items' => [''], 'type' => 'unordered', 'class' => ''],
            'cta' => ['title' => '', 'description' => '', 'button_text' => '', 'button_url' => '', 'class' => ''],
            'video' => ['src' => '', 'type' => 'video/mp4', 'controls' => true, 'class' => ''],
            default => [],
        };
    }
};
?>

<div class="space-y-4">
    <flux:modal.trigger name="block-selector">
        <flux:button variant="primary">
            <flux:icon icon="plus" variant="outline" class="w-4 h-4 mr-2" />
            Add Block
        </flux:button>
    </flux:modal.trigger>

    @if(empty($blocks))
        <div class="text-center py-12 border-2 border-dashed border-admin-border rounded-xl">
            <p class="text-admin-text-muted">No content blocks yet. Click "Add Block" to get started.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($blocks as $index => $block)
                <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-4 group shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-admin-text">{{ $availableBlockTypes[$block['type']] ?? $block['type'] }}</span>
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            @if($index > 0)
                                <flux:button size="sm" variant="ghost" wire:click="moveBlock({{ $index }}, {{ $index - 1 }})">
                                    <flux:icon icon="chevron-up" variant="outline" class="w-4 h-4" />
                                </flux:button>
                            @endif
                            @if($index < count($blocks) - 1)
                                <flux:button size="sm" variant="ghost" wire:click="moveBlock({{ $index }}, {{ $index + 1 }})">
                                    <flux:icon icon="chevron-down" variant="outline" class="w-4 h-4" />
                                </flux:button>
                            @endif
                            <flux:button size="sm" variant="ghost" wire:click="removeBlock('{{ $block['id'] }}')">
                                <flux:icon icon="trash" variant="outline" class="w-4 h-4 text-red-400" />
                            </flux:button>
                        </div>
                    </div>

                    @include('components.blocks.' . $block['type'] . '-editor', ['block' => $block, 'index' => $index])
                </div>
            @endforeach
        </div>
    @endif

    <flux:modal name="block-selector">
        <x-slot name="heading">Select Block Type</x-slot>
        <div class="grid grid-cols-2 gap-3 p-4">
            @foreach($availableBlockTypes as $type => $label)
                <flux:button variant="ghost" wire:click="addBlock('{{ $type }}')">
                    {{ $label }}
                </flux:button>
            @endforeach
        </div>
    </flux:modal>
</div>

@script
<script>
    function initAllParagraphEditors() {
        if (typeof CKEDITOR === 'undefined') {
            console.warn('CKEditor not loaded yet, retrying in 500ms...');
            setTimeout(initAllParagraphEditors, 500);
            return;
        }

        // Find all paragraph editor textareas
        const textareas = document.querySelectorAll('[id^="paragraph-editor-"]');
        
        textareas.forEach((textarea) => {
            const editorId = textarea.id;
            const index = editorId.replace('paragraph-editor-', '');

            // Destroy existing instance if any
            if (CKEDITOR.instances[editorId]) {
                CKEDITOR.instances[editorId].destroy();
            }

            // Initialize CKEditor
            CKEDITOR.replace(editorId, {
                height: 200,
                uiColor: '#16161d',
                contentsCss: ['{{ asset("vendor/ckeditor/contents.css") }}', '{{ asset("css/ckeditor-custom.css") }}']
            });

            // Sync CKEditor content to Livewire on change
            CKEDITOR.instances[editorId].on('change', function() {
                const content = CKEDITOR.instances[editorId].getData();
                $wire.set('blocks.' + index + '.attributes.content', content);
            });
        });
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        initAllParagraphEditors();
    });

    // Re-initialize after Livewire updates (for dynamic blocks)
    document.addEventListener('livewire:update', function() {
        setTimeout(initAllParagraphEditors, 100);
    });
</script>
@endscript
