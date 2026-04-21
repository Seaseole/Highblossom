<x-layouts::admin title="Quote Details">
    <div class="p-6">
        <div class="mb-6">
            <a href="{{ route('admin.quotes.index') }}" class="text-[#DC2626] hover:text-[#FAFAFA] text-sm transition-colors">
                &larr; Back to Quotes
            </a>
        </div>

        <div class="admin-glass-card rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex justify-between items-center">
                <h1 class="text-xl font-bold text-[#FAFAFA] font-headline">Quote Request #{{ $quote->id }}</h1>
                @if($quote->status === 'pending')
                    <span class="admin-badge" style="background: rgba(59, 130, 246, 0.2); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.3);">
                        Pending
                    </span>
                @elseif($quote->status === 'contacted')
                    <span class="admin-badge" style="background: rgba(234, 179, 8, 0.2); color: #EAB308; border: 1px solid rgba(234, 179, 8, 0.3);">
                        Contacted
                    </span>
                @elseif($quote->status === 'completed')
                    <span class="admin-badge admin-badge-active">
                        Completed
                    </span>
                @elseif($quote->status === 'cancelled')
                    <span class="admin-badge admin-badge-inactive">
                        Cancelled
                    </span>
                @endif
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="admin-glass-card rounded-xl p-5">
                        <h3 class="text-lg font-medium text-[#FAFAFA] mb-4 font-headline">Customer Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-[#A1A1AA]">Name</dt>
                                <dd class="text-sm text-[#FAFAFA]">{{ $quote->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-[#A1A1AA]">Phone</dt>
                                <dd class="text-sm text-[#FAFAFA]">{{ $quote->phone }}</dd>
                            </div>
                            @if($quote->email)
                                <div>
                                    <dt class="text-sm font-medium text-[#A1A1AA]">Email</dt>
                                    <dd class="text-sm text-[#FAFAFA]">{{ $quote->email }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div class="admin-glass-card rounded-xl p-5">
                        <h3 class="text-lg font-medium text-[#FAFAFA] mb-4 font-headline">Vehicle Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-[#A1A1AA]">Vehicle Type</dt>
                                <dd class="text-sm text-[#FAFAFA]">{{ ucfirst($quote->vehicle_type) }}</dd>
                            </div>
                            @if($quote->make_model)
                                <div>
                                    <dt class="text-sm font-medium text-[#A1A1AA]">Make & Model</dt>
                                    <dd class="text-sm text-[#FAFAFA]">{{ $quote->make_model }}</dd>
                                </div>
                            @endif
                            @if($quote->reg_number)
                                <div>
                                    <dt class="text-sm font-medium text-[#A1A1AA]">Registration Number</dt>
                                    <dd class="text-sm text-[#FAFAFA]">{{ $quote->reg_number }}</dd>
                                </div>
                            @endif
                            @if($quote->year)
                                <div>
                                    <dt class="text-sm font-medium text-[#A1A1AA]">Year</dt>
                                    <dd class="text-sm text-[#FAFAFA]">{{ $quote->year }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="admin-glass-card rounded-xl p-5">
                        <h3 class="text-lg font-medium text-[#FAFAFA] mb-4 font-headline">Service Details</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-[#A1A1AA]">Glass Type</dt>
                                <dd class="text-sm text-[#FAFAFA]">{{ $quote->glassType->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-[#A1A1AA]">Service Type</dt>
                                <dd class="text-sm text-[#FAFAFA]">{{ $quote->serviceType->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-[#A1A1AA]">Mobile Service</dt>
                                <dd class="text-sm text-[#FAFAFA]">{{ $quote->mobile_service ? 'Yes' : 'No' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="admin-glass-card rounded-xl p-5">
                        <h3 class="text-lg font-medium text-[#FAFAFA] mb-4 font-headline">Quote Details</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-[#A1A1AA]">Received At</dt>
                                <dd class="text-sm text-[#FAFAFA]">{{ $quote->created_at->format('F j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-[#A1A1AA]">Status</dt>
                                <dd class="text-sm text-[#FAFAFA]">{{ ucfirst($quote->status) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if($quote->image_path)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-[#FAFAFA] mb-4 font-headline">Uploaded Image</h3>
                        <div class="admin-glass-card rounded-xl p-4">
                            <img src="{{ Storage::url($quote->image_path) }}" alt="Glass damage image" class="max-w-full h-auto rounded-xl border border-white/10">
                        </div>
                    </div>
                @endif

                <div class="border-t border-white/5 pt-6">
                    <h3 class="text-lg font-medium text-[#FAFAFA] mb-4 font-headline">Update Status</h3>
                    <form action="{{ route('admin.quotes.updateStatus', $quote) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="flex gap-4 items-end">
                            <div class="flex-1">
                                <label for="status" class="block text-sm font-medium text-[#A1A1AA] mb-2">Status</label>
                                <select id="status" name="status" class="admin-form-input">
                                    <option value="pending" {{ $quote->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="contacted" {{ $quote->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="completed" {{ $quote->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $quote->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="admin-action-btn admin-action-btn-primary">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <form action="{{ route('admin.quotes.destroy', $quote) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this quote?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-action-btn admin-action-btn-ghost" style="color: #DC2626; border-color: rgba(220, 38, 38, 0.3);">
                            Delete Quote
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts::admin>
