<x-layouts::admin title="Message Details">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <a href="{{ route('admin.contact-messages.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    ← Back to Messages
                </a>
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">
                    {{ $message->subject }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400">Received {{ $message->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
            
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $message->is_read ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                {{ $message->is_read ? 'Read' : 'Unread' }}
            </span>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <!-- Sender Information -->
                <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Sender Information</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $message->name }}</dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <a href="mailto:{{ $message->email }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $message->email }}</a>
                            </dd>
                        </div>
                        @if($message->phone)
                            <div class="space-y-1">
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    <a href="tel:{{ $message->phone }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $message->phone }}</a>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Message Content -->
                <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Message</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/5 leading-relaxed">
                        {{ $message->message }}
                    </p>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Actions Card -->
                <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Actions</h2>
                    
                    @if(!$message->is_read)
                        <form action="{{ route('admin.contact-messages.mark-read', $message) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                                Mark as Read
                            </button>
                        </form>
                    @endif

                    <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="w-full inline-block text-center bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-900 dark:text-white font-medium py-2.5 px-6 rounded-full text-sm transition-all">
                        Reply via Email
                    </a>
                </div>

                <!-- Delete Action -->
                <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-sm font-medium text-red-600 dark:text-red-400 hover:opacity-75 transition-opacity">
                        Delete Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts::admin>