<x-layouts::admin title="Contact Messages">
    <div class="p-6">
        {{-- Header --}}
        <div class="admin-section-header mb-6">
            <h1 class="admin-section-title">Contact Messages</h1>
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.contact-messages.index') }}" method="GET" class="flex items-center gap-2">
                    <select name="status" class="admin-form-input text-sm py-2">
                        <option value="">All Messages</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread Only</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read Only</option>
                    </select>
                    <button type="submit" class="admin-action-btn admin-action-btn-secondary text-sm">
                        Filter
                    </button>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="admin-glass-card rounded-3xl shadow-black/20 overflow-hidden">
            <table class="min-w-full divide-y divide-admin-border-subtle">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Sender</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Received</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-admin-text uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border-subtle">
                    @forelse($messages as $message)
                        <tr class="transition-colors duration-200 {{ $message->is_read ? '' : 'bg-admin-accent/5' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    @if(!$message->is_read)
                                        <span class="w-2 h-2 rounded-full bg-admin-accent animate-pulse shrink-0"></span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-admin-text-muted/30 shrink-0"></span>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-admin-text">{{ $message->name }}</div>
                                        <div class="text-xs text-admin-text-muted">{{ $message->email }}</div>
                                        @if($message->phone)
                                            <div class="text-xs text-admin-text-muted">{{ $message->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-admin-text {{ $message->is_read ? '' : 'text-admin-accent' }}">{{ $message->subject }}</div>
                                <div class="text-xs text-admin-text-muted truncate max-w-xs">{{ Str::limit($message->message, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($message->is_read)
                                    <span class="admin-badge admin-badge-inactive">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-2"></span>
                                        Read
                                    </span>
                                @else
                                    <span class="admin-badge admin-badge-danger">
                                        <span class="w-1.5 h-1.5 rounded-full bg-admin-accent mr-2 animate-pulse"></span>
                                        Unread
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-admin-text-muted">
                                {{ $message->created_at->format('M j, Y') }}
                                <br>
                                <span class="text-xs">{{ $message->created_at->format('g:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.contact-messages.show', $message) }}" class="admin-action-btn admin-action-btn-secondary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 text-admin-text-muted mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                                <p class="text-admin-text-muted">No messages found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    </div>
</x-layouts::admin>
