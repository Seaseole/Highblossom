<x-layouts::admin title="Contact Messages">
    <div class="p-6">
        {{-- Header --}}
        <div class="admin-section-header mb-6">
            <h1 class="admin-section-title">Contact Messages</h1>
        </div>

        {{-- Tab Navigation --}}
        <div class="mb-6">
            <nav class="flex space-x-1 p-1 bg-admin-surface-alt rounded-xl border border-admin-border-subtle">
                {{-- All Messages Tab --}}
                <a href="{{ route('admin.contact-messages.index') }}" 
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ !request('status') ? 'bg-admin-accent/10 border border-admin-accent/30 text-admin-accent' : 'text-admin-text-muted hover:text-admin-text hover:bg-admin-surface' }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    All Messages
                </a>

                {{-- Unread Tab --}}
                <a href="{{ route('admin.contact-messages.index', ['status' => 'unread']) }}" 
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') === 'unread' ? 'bg-admin-accent/10 border border-admin-accent/30 text-admin-accent' : 'text-admin-text-muted hover:text-admin-text hover:bg-admin-surface' }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                    </svg>
                    Unread
                </a>

                {{-- Read Tab --}}
                <a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}" 
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') === 'read' ? 'bg-admin-accent/10 border border-admin-accent/30 text-admin-accent' : 'text-admin-text-muted hover:text-admin-text hover:bg-admin-surface' }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Read
                </a>
            </nav>
        </div>

        {{-- Table --}}
        <div class="admin-glass-card rounded-3xl shadow-black/20 overflow-hidden">
            <table class='w-full min-w-[800px] divide-y divide-admin-border-subtle'>
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
