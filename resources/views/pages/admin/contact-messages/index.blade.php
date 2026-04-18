<?php

use App\Domains\Content\Models\ContactMessage;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';

    public function deleteMessage(int $id): void
    {
        ContactMessage::find($id)?->delete();
        $this->dispatch('notify', ['message' => 'Message deleted successfully!', 'type' => 'success']);
    }

    public function markAsRead(int $id): void
    {
        $message = ContactMessage::find($id);
        if ($message) {
            $message->markAsRead();
            $this->dispatch('notify', ['message' => 'Message marked as read!', 'type' => 'success']);
        }
    }

    public function with(): array
    {
        return [
            'messages' => ContactMessage::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('subject', 'like', '%' . $this->search . '%');
            })->orderBy('created_at', 'desc')->paginate(10),
            'unreadCount' => ContactMessage::unread()->count(),
        ];
    }
}; ?>

<flux:main>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-6">
            <div>
                <flux:heading size="xl" level="1">{{ __('Contact Messages') }}</flux:heading>
                @if ($unreadCount > 0)
                    <flux:subheading class="text-indigo-600 dark:text-indigo-400">
                        {{ trans_choice('{1} :count unread message|[2,*] :count unread messages', $unreadCount, ['count' => $unreadCount]) }}
                    </flux:subheading>
                @endif
            </div>
        </div>

        <div class="mb-6 flex gap-4">
            <div class="flex-1">
                <flux:input wire:model.live="search" :placeholder="__('Search messages...')" icon="magnifying-glass" />
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('From') }}</flux:table.column>
                    <flux:table.column>{{ __('Subject') }}</flux:table.column>
                    <flux:table.column>{{ __('Date') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column align="end"></flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($messages as $message)
                        <flux:table.row :key="$message->id" class="{{ $message->is_read ? '' : 'bg-indigo-50/50 dark:bg-indigo-900/10' }}">
                            <flux:table.cell>
                                <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $message->name }}</div>
                                <div class="text-xs text-zinc-500">{{ $message->email }}</div>
                                @if ($message->phone)
                                    <div class="text-xs text-zinc-400">{{ $message->phone }}</div>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $message->subject }}</div>
                                <div class="text-xs text-zinc-500 truncate max-w-xs">{{ Str::limit($message->message, 100) }}</div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="text-xs text-zinc-500">{{ $message->created_at->format('M d, Y H:i') }}</div>
                            </flux:table.cell>
                            <flux:table.cell>
                                @if ($message->is_read)
                                    <flux:badge size="sm" color="zinc">{{ __('Read') }}</flux:badge>
                                @else
                                    <flux:badge size="sm" color="indigo">{{ __('New') }}</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell align="end">
                                <div class="flex justify-end gap-1">
                                    @if (!$message->is_read)
                                        <flux:button variant="ghost" size="sm" icon="check" wire:click="markAsRead({{ $message->id }})" tooltip="{{ __('Mark as Read') }}" />
                                    @endif
                                    <flux:button variant="ghost" size="sm" icon="trash" wire:click="deleteMessage({{ $message->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-600 hover:text-red-700" />
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center py-12 text-zinc-500">
                                {{ __('No messages found.') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    </div>
</flux:main>
