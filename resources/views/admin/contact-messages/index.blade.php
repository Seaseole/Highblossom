<x-layouts::admin title="Contact Messages">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Contact Messages</h1>
            <p class="text-gray-500 dark:text-gray-400">Manage incoming contact requests.</p>
        </div>

        <!-- Filters -->
        <div class="flex items-center gap-2 p-1 bg-gray-100 dark:bg-white/5 rounded-full w-max">
            @foreach(['' => 'All', 'unread' => 'Unread', 'read' => 'Read'] as $status => $label)
                <a href="{{ route('admin.contact-messages.index', $status ? ['status' => $status] : []) }}" 
                   class="px-6 py-2 text-sm font-medium rounded-full transition-all {{ request('status') === $status || (!request('status') && !$status) ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 overflow-hidden shadow-sm">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sender</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Received</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse($messages as $message)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200 {{ !$message->is_read ? 'bg-gray-50/50 dark:bg-white/[0.02]' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if(!$message->is_read)
                                        <div class="w-2 h-2 rounded-full bg-gray-900 dark:bg-white shrink-0"></div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $message->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $message->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white font-medium">{{ $message->subject }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ Str::limit($message->message, 50) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ !$message->is_read ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400' }}">
                                    {{ $message->is_read ? 'Read' : 'Unread' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                {{ $message->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.contact-messages.show', $message) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:opacity-75 transition-opacity">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No messages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $messages->links() }}
        </div>
    </div>
</x-layouts::admin>
