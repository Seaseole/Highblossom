<x-layouts::admin title="Quotes">
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-4xl font-bold tracking-tight text-admin-text font-headline leading-none">
                    Quote Requests
                </h1>
                <p class="text-admin-text-muted text-sm max-w-lg">
                    Monitor and manage incoming requests for architectural glass and aluminum solutions.
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <form method="GET" action="{{ route('admin.quotes.index') }}" class="flex items-center gap-3">
                    <select name="status" class="admin-form-input font-body text-xs py-2 pr-8 min-w-[140px]">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="contacted" {{ $status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary py-2">
                        <span class="text-[10px] font-bold uppercase tracking-widest">Filter</span>
                    </button>
                    @if($status)
                        <a href="{{ route('admin.quotes.index') }}" class="text-admin-accent hover:text-admin-accent/80 text-[10px] font-bold uppercase tracking-widest transition-colors ml-1">Clear</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="admin-table admin-glass-card shadow-2xl shadow-black/20">
            <table class="min-w-full divide-y divide-admin-border-subtle">
                <thead class="bg-admin-accent/5">
                    <tr>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Customer</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Project/Vehicle</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Service</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Status</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Received</th>
                        <th scope="col" class="px-8 py-5 text-right text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border-subtle">
                    @forelse($quotes as $quote)
                        <tr class="group hover:bg-admin-accent/5 transition-all duration-300 ease-out-expo {{ $quote->status === 'pending' ? 'bg-admin-accent/5' : '' }}">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <span class="text-base font-bold text-admin-text font-headline tracking-tight">{{ $quote->name }}</span>
                                    <span class="text-xs font-mono text-admin-text-muted">{{ $quote->phone }}</span>
                                    @if($quote->email)
                                        <span class="text-[10px] text-admin-text-muted opacity-60 truncate max-w-[150px]">{{ $quote->email }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <span class="text-sm font-bold text-admin-text uppercase tracking-tight">{{ $quote->vehicle_type }}</span>
                                    @if($quote->make_model)
                                        <span class="text-xs text-admin-text-muted font-body italic">{{ $quote->make_model }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-sm">
                                <div class="flex flex-col gap-1">
                                    <span class="text-admin-text font-bold tracking-tight">{{ $quote->glassType->name ?? 'N/A' }}</span>
                                    <span class="text-admin-text-muted text-xs">{{ $quote->serviceType->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex flex-wrap gap-2">
                                    @if($quote->status === 'pending')
                                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-blue-500/10 border border-blue-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)] pulse-slow"></span>
                                            <span class="text-[9px] font-bold text-blue-500 uppercase tracking-widest font-body">Pending</span>
                                        </div>
                                    @elseif($quote->status === 'contacted')
                                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-amber-500/10 border border-amber-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                                            <span class="text-[9px] font-bold text-amber-500 uppercase tracking-widest font-body">Contacted</span>
                                        </div>
                                    @elseif($quote->status === 'completed')
                                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-green-500/10 border border-green-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                                            <span class="text-[9px] font-bold text-green-500 uppercase tracking-widest font-body">Completed</span>
                                        </div>
                                    @elseif($quote->status === 'cancelled')
                                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-admin-text-muted/10 border border-admin-text-muted/20 opacity-50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-admin-text-muted"></span>
                                            <span class="text-[9px] font-bold text-admin-text-muted uppercase tracking-widest font-body">Cancelled</span>
                                        </div>
                                    @endif
                                    
                                    @if($quote->mobile_service)
                                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-purple-500/10 border border-purple-500/20">
                                            <svg class="w-2.5 h-2.5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-[9px] font-bold text-purple-500 uppercase tracking-widest font-body">Mobile</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex flex-col text-[10px] font-mono text-admin-text-muted">
                                    <span>{{ $quote->created_at->format('M j, Y') }}</span>
                                    <span class="opacity-50 tracking-tighter">{{ $quote->created_at->format('g:i A') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <a href="{{ route('admin.quotes.show', $quote) }}" class="admin-action-btn admin-action-btn-ghost group/view">
                                    <svg class="w-4 h-4 transition-colors group-hover/view:text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span class="font-bold tracking-tight text-sm">View</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-20 h-20 rounded-full bg-admin-surface-alt flex items-center justify-center border border-admin-border-subtle">
                                        <svg class="w-10 h-10 text-admin-text-muted/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-admin-text-muted font-body text-sm">No quote requests found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quotes->hasPages())
            <div class="pt-4">
                {{ $quotes->links() }}
            </div>
        @endif
    </div>
</x-layouts::admin>

