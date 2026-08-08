<x-layouts::admin title="Quote Details">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <a
                    href="{{ route('admin.quotes.index') }}"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >
                    ← Back to Quotes
                </a>
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                    Quote #{{ $quote->id }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400">
                    Received on {{ $quote->created_at->format('F j, Y \a\t g:i A') }}
                </p>
            </div>

            @php
                $statusColors = [
                    'pending' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                    'contacted' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                    'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                    'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400',
                ];
            @endphp
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$quote->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst($quote->status) }}
            </span>
        </div>

        <!-- Content -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="space-y-8 lg:col-span-2">
                <!-- Customer Info -->
                <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Customer Information</h2>
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Name</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $quote->name }}</dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Phone</dt>
                            <dd class="font-mono text-sm font-medium text-gray-900 dark:text-white">
                                {{ $quote->phone }}
                            </dd>
                        </div>
                        @if ($quote->email)
                            <div class="space-y-1 sm:col-span-2">
                                <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Email</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $quote->email }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Asset Specs -->
                <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Project Specifications</h2>
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Vehicle Type</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $quote->vehicle_type }}
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Make & Model</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $quote->make_model ?? '—' }}
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Glass Material</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $quote->glassType->name ?? 'Unspecified' }}
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Service Type</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $quote->serviceType->name ?? 'Unspecified' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Update Status -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Update Status</h2>
                    <form action="{{ route('admin.quotes.updateStatus', $quote) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <select
                            name="status"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        >
                            @foreach (['pending', 'contacted', 'completed', 'cancelled'] as $s)
                                <option value="{{ $s }}" {{ $quote->status === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        <button
                            type="submit"
                            class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                        >
                            Update Status
                        </button>
                    </form>
                </div>

                <!-- Delete -->
                <form
                    action="{{ route('admin.quotes.destroy', $quote) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this quote request?');"
                >
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="w-full text-sm font-medium text-red-600 transition-opacity hover:opacity-75 dark:text-red-400"
                    >
                        Delete Request
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts::admin>
