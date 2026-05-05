<x-layouts::admin title="Quote Details">
    <div class="p-8 max-w-6xl mx-auto space-y-8">
        {{-- Header & Navigation --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3 text-admin-text-muted mb-2">
                    <a href="{{ route('admin.quotes.index') }}" class="hover:text-admin-accent transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] font-body">Quotes / Request #{{ $quote->id }}</span>
                </div>
                <h1 class="text-4xl font-bold tracking-tight text-admin-text font-headline leading-none">
                    Quote Details
                </h1>
            </div>

            <div class="flex items-center gap-3">
                @if($quote->status === 'pending')
                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20">
                        <span class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.6)] pulse-slow"></span>
                        <span class="text-xs font-bold text-blue-500 uppercase tracking-widest font-body">Pending Request</span>
                    </div>
                @elseif($quote->status === 'contacted')
                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/20">
                        <span class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.6)]"></span>
                        <span class="text-xs font-bold text-amber-500 uppercase tracking-widest font-body">Contacted</span>
                    </div>
                @elseif($quote->status === 'completed')
                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-green-500/10 border border-green-500/20">
                        <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]"></span>
                        <span class="text-xs font-bold text-green-500 uppercase tracking-widest font-body">Completed</span>
                    </div>
                @elseif($quote->status === 'cancelled')
                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-admin-text-muted/10 border border-admin-text-muted/20 opacity-60">
                        <span class="w-2 h-2 rounded-full bg-admin-text-muted"></span>
                        <span class="text-xs font-bold text-admin-text-muted uppercase tracking-widest font-body">Cancelled</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Details Column --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="admin-glass-card p-8 rounded-3xl shadow-2xl shadow-black/20 space-y-10">
                    {{-- Customer Section --}}
                    <section class="space-y-6">
                        <h3 class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Client Profile</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-1">
                                <span class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest block">Full Name</span>
                                <span class="text-lg font-bold text-admin-text font-headline tracking-tight">{{ $quote->name }}</span>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest block">Contact Number</span>
                                <span class="text-lg font-mono text-admin-text tracking-tighter">{{ $quote->phone }}</span>
                            </div>
                            @if($quote->email)
                                <div class="space-y-1">
                                    <span class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest block">Email Address</span>
                                    <span class="text-sm text-admin-text font-body opacity-80">{{ $quote->email }}</span>
                                </div>
                            @endif
                        </div>
                    </section>

                    {{-- Technical Information --}}
                    <section class="space-y-6 pt-10 border-t border-admin-border-subtle">
                        <h3 class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Asset Specifications</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-1">
                                <span class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest block">Vehicle Type</span>
                                <span class="text-base font-bold text-admin-text uppercase tracking-tight">{{ $quote->vehicle_type }}</span>
                            </div>
                            @if($quote->make_model)
                                <div class="space-y-1">
                                    <span class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest block">Make & Model</span>
                                    <span class="text-base font-bold text-admin-text tracking-tight">{{ $quote->make_model }}</span>
                                </div>
                            @endif
                            @if($quote->reg_number)
                                <div class="space-y-1">
                                    <span class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest block">Registration</span>
                                    <span class="text-base font-mono font-bold text-admin-accent tracking-widest">{{ $quote->reg_number }}</span>
                                </div>
                            @endif
                            @if($quote->year)
                                <div class="space-y-1">
                                    <span class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest block">Model Year</span>
                                    <span class="text-base font-bold text-admin-text tracking-tight">{{ $quote->year }}</span>
                                </div>
                            @endif
                        </div>
                    </section>

                    {{-- Service Selection --}}
                    <section class="space-y-6 pt-10 border-t border-admin-border-subtle">
                        <h3 class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Service Scope</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="space-y-1">
                                <span class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest block">Glass Material</span>
                                <span class="text-sm font-bold text-admin-text tracking-tight">{{ $quote->glassType->name ?? 'Unspecified' }}</span>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest block">Service Type</span>
                                <span class="text-sm font-bold text-admin-text tracking-tight">{{ $quote->serviceType->name ?? 'Unspecified' }}</span>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest block">Deployment</span>
                                <div class="flex items-center gap-2">
                                    @if($quote->mobile_service)
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 shadow-[0_0_8px_rgba(168,85,247,0.5)]"></span>
                                        <span class="text-xs font-bold text-purple-500 uppercase tracking-widest">Mobile Unit</span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-admin-text-muted"></span>
                                        <span class="text-xs font-bold text-admin-text-muted uppercase tracking-widest">In-Studio</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>

                    @if($quote->image_path)
                        <section class="space-y-6 pt-10 border-t border-admin-border-subtle">
                            <h3 class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Visual Assessment</h3>
                            <div class="relative group rounded-2xl overflow-hidden border border-admin-border-subtle shadow-xl max-w-xl">
                                <img src="{{ Storage::url($quote->image_path) }}" alt="Assessment image" class="w-full h-auto grayscale group-hover:grayscale-0 transition-all duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                    <span class="text-[10px] font-bold text-white uppercase tracking-widest">High-Resolution Damage Report</span>
                                </div>
                            </div>
                        </section>
                    @endif
                </div>
            </div>

            {{-- Sidebar / Configuration Column --}}
            <div class="space-y-8">
                {{-- Metadata Card --}}
                <div class="admin-glass-card p-6 rounded-3xl shadow-xl shadow-black/10 space-y-6">
                    <div class="space-y-4">
                        <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Request Audit</label>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-admin-border-subtle/50">
                                <span class="text-[10px] font-medium text-admin-text-muted uppercase tracking-widest">Received</span>
                                <span class="text-[10px] font-mono text-admin-text">{{ $quote->created_at->format('M j, Y - g:i A') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-admin-border-subtle/50">
                                <span class="text-[10px] font-medium text-admin-text-muted uppercase tracking-widest">Priority</span>
                                <span class="text-[10px] font-bold text-admin-accent uppercase tracking-widest">High</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-4">
                        <label for="status" class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Update Pipeline</label>
                        <form action="{{ route('admin.quotes.updateStatus', $quote) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <select id="status" name="status" class="admin-form-input text-xs font-bold uppercase tracking-widest">
                                <option value="pending" {{ $quote->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="contacted" {{ $quote->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="completed" {{ $quote->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $quote->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="admin-action-btn admin-action-btn-primary w-full justify-center py-3 shadow-lg shadow-admin-accent/20 focus:ring-admin-accent">
                                <span class="text-xs font-bold uppercase tracking-[0.2em]">Commit Status</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Dangerous Actions --}}
                <div class="pt-4 px-2">
                    <form action="{{ route('admin.quotes.destroy', $quote) }}" method="POST" onsubmit="return confirm('Archive this request permanently?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-[10px] font-bold text-admin-accent/40 hover:text-admin-accent uppercase tracking-[0.3em] transition-colors w-full text-center py-4 border-2 border-dashed border-admin-accent/10 hover:border-admin-accent/30 rounded-2xl">
                            Delete Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts::admin>

