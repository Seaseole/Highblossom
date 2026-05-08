<x-layouts::admin title="Message Details">
    <div class="p-6 lg:p-8">
        {{-- Header with improved spacing --}}
        <div class="mb-8">
            <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center gap-2 text-sm text-admin-text-muted hover:text-admin-accent transition-all duration-300 group">
                <svg class="w-4 h-4 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="font-medium">Back to Messages</span>
            </a>
        </div>

        {{-- Enhanced Title Section with better visual hierarchy --}}
        <div class="mb-12">
            <div class="flex items-start gap-6">
                @if($message->is_read)
                    <span class="mt-2 w-4 h-4 rounded-full bg-gray-400 shrink-0"></span>
                @else
                    <span class="mt-2 w-4 h-4 rounded-full bg-admin-accent animate-pulse shrink-0 shadow-lg shadow-admin-accent/50"></span>
                @endif
                <div class="flex-1">
                    <h1 class="font-headline text-3xl lg:text-4xl font-bold text-admin-text tracking-tight leading-tight">
                        {{ $message->subject }}
                    </h1>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mt-3">
                        <p class="text-admin-text-muted text-sm">Received {{ $message->created_at->format('F j, Y') }}</p>
                        <p class="text-admin-text-muted text-sm">{{ $message->created_at->format('g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enhanced Content Grid with better spacing --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 lg:gap-6">
            {{-- Left Column - Sender Info & Message with improved layout --}}
            <div class="xl:col-span-2 space-y-8">
                {{-- Enhanced Sender Information --}}
                <div class="admin-glass-card rounded-3xl shadow-black/20 p-8">
                    <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-6 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-admin-surface-alt flex items-center justify-center transition-all duration-300 hover:bg-admin-surface hover:scale-105">
                            <svg class="w-5 h-5 text-admin-accent transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        Sender Information
                    </h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-1">Name</dt>
                            <dd class="text-base font-medium text-admin-text leading-relaxed">{{ $message->name }}</dd>
                        </div>
                        <div class="space-y-2">
                            <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-1">Email</dt>
                            <dd class="text-base font-medium text-admin-text leading-relaxed">
                                <a href="mailto:{{ $message->email }}" class="text-admin-accent hover:text-admin-accent/80 transition-colors duration-300 underline decoration-2 underline-offset-2 hover:underline-offset-4">{{ $message->email }}</a>
                            </dd>
                        </div>
                        @if($message->phone)
                            <div class="space-y-2 sm:col-span-2">
                                <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-1">Phone</dt>
                                <dd class="text-base font-medium text-admin-text leading-relaxed">
                                    <a href="tel:{{ $message->phone }}" class="text-admin-accent hover:text-admin-accent/80 transition-colors duration-300 underline decoration-2 underline-offset-2 hover:underline-offset-4">{{ $message->phone }}</a>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Enhanced Message Content --}}
                <div class="admin-glass-card rounded-3xl shadow-black/20 p-8">
                    <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-6 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-admin-surface-alt flex items-center justify-center transition-all duration-300 hover:bg-admin-surface hover:scale-105">
                            <svg class="w-5 h-5 text-admin-accent transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </span>
                        Message
                    </h2>
                    <div class="p-6 bg-admin-surface-alt/50 rounded-2xl border border-admin-border-subtle">
                        <p class="text-base text-admin-text whitespace-pre-wrap leading-relaxed">{{ $message->message }}</p>
                    </div>
                </div>
            </div>

            {{-- Right Column - Status & Actions with enhanced spacing --}}
            <div class="space-y-8">
                {{-- Enhanced Status Card --}}
                <div class="admin-glass-card rounded-3xl shadow-black/20 p-8">
                    <h2 class="font-headline text-sm font-semibold text-admin-text uppercase tracking-wide mb-6">Status</h2>
                    <div class="mb-6">
                        @if($message->is_read)
                            <span class="admin-badge admin-badge-inactive w-full justify-center py-3 text-sm">
                                <span class="w-2 h-2 rounded-full bg-gray-400 mr-2"></span>
                                Read
                            </span>
                        @else
                            <span class="admin-badge admin-badge-danger w-full justify-center py-3 text-sm">
                                <span class="w-2 h-2 rounded-full bg-admin-accent mr-2 animate-pulse"></span>
                                Unread
                            </span>
                        @endif
                    </div>

                    @if(!$message->is_read)
                        {{-- Enhanced Mark as Read Form --}}
                        <form action="{{ route('admin.contact-messages.mark-read', $message) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="admin-action-btn admin-action-btn-primary w-full text-sm flex items-center justify-center gap-3 py-3 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-admin-accent/25">
                                <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Mark as Read
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Enhanced Quick Actions --}}
                <div class="admin-glass-card rounded-3xl shadow-black/20 p-8">
                    <h2 class="font-headline text-sm font-semibold text-admin-text uppercase tracking-wide mb-6">Quick Actions</h2>
                    <div class="space-y-4">
                        <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="w-full px-4 py-4 bg-admin-surface-alt/50 border border-admin-border-subtle rounded-2xl text-sm font-medium text-admin-text hover:bg-admin-surface-alt hover:border-admin-accent/30 transition-all duration-300 flex items-center justify-center gap-3 group">
                            <svg class="w-5 h-5 text-admin-accent transition-all duration-300 group-hover:scale-110 group-hover:rotate-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Reply via Email
                        </a>
                    </div>
                </div>

                {{-- Enhanced Delete Action --}}
                <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');" class="space-y-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-4 border border-admin-accent/30 rounded-2xl text-sm font-medium text-admin-accent hover:bg-admin-accent/10 hover:border-admin-accent/50 transition-all duration-300 flex items-center justify-center gap-3 group">
                        <svg class="w-5 h-5 transition-all duration-300 group-hover:scale-110 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts::admin>
