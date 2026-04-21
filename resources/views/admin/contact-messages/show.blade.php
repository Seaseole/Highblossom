<x-layouts::admin title="Message Details">
    <div class="p-6">
        <div class="mb-6">
            <a href="{{ route('admin.contact-messages.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                &larr; Back to Messages
            </a>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-900">{{ $message->subject }}</h1>
                @if($message->is_read)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Read
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Unread
                    </span>
                @endif
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Sender Information</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="text-sm text-gray-900">{{ $message->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $message->email }}</dd>
                            </div>
                            @if($message->phone)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="text-sm text-gray-900">{{ $message->phone }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Message Details</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Received At</dt>
                                <dd class="text-sm text-gray-900">{{ $message->created_at->format('F j, Y g:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Message</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-600 hover:bg-red-50">
                            Delete Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts::admin>
