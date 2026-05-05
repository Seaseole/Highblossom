<x-layouts::admin title="Edit Service Type">
    <div class="p-8 max-w-5xl mx-auto space-y-10">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3 text-admin-text-muted mb-2">
                    <a href="{{ route('admin.service-types.index') }}" class="hover:text-admin-accent transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] font-body">Service Types / Edit</span>
                </div>
                <h1 class="text-4xl font-bold tracking-tight text-admin-text font-headline leading-none">
                    Edit Service Type
                </h1>
                <p class="text-admin-text-muted text-sm max-w-lg">
                    Modify the architectural service category details and visibility settings.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.service-types.update', $serviceType) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PUT')

            {{-- Main Form Area --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="admin-glass-card p-8 rounded-3xl shadow-2xl shadow-black/20 space-y-8">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $serviceType->name) }}" required class="admin-form-input font-headline text-lg font-bold tracking-tight" placeholder="e.g. Glass Replacement & Repair">
                                @error('name')
                                    <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Area --}}
            <div class="space-y-6">
                <div class="admin-glass-card p-6 rounded-3xl shadow-xl shadow-black/10 space-y-6">
                    <div class="space-y-4">
                        <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Configuration</label>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div class="space-y-2">
                                <label for="sort_order" class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest font-body ml-1">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $serviceType->sort_order) }}" class="admin-form-input text-sm" placeholder="0">
                                @error('sort_order')
                                    <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="p-4 bg-admin-surface-alt/30 rounded-xl border border-admin-border-subtle group hover:border-admin-accent/20 transition-colors">
                                <label class="ui-checkbox-wrapper flex items-center gap-3 cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" class="ui-checkbox-input" {{ old('is_active', $serviceType->is_active) ? 'checked' : '' }}>
                                    <div class="ui-checkbox-check">
                                        <svg viewBox="0 0 18 18">
                                            <path d="M1,9 L1,3.5 C1,2.11928813 2.11928813,1 3.5,1 L14.5,1 C15.8807119,1 17,2.11928813 17,3.5 L17,14.5 C17,15.8807119 15.8807119,17 14.5,17 L3.5,17 C2.11928813,17 1,15.8807119 1,14.5 L1,9 Z"></path>
                                            <polyline points="1 9 7 14 15 4"></polyline>
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-admin-text uppercase tracking-widest font-body">Visible</span>
                                        <span class="text-[10px] text-admin-text-muted font-body">Visible in quote forms</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="admin-action-btn admin-action-btn-primary w-full justify-center py-4 shadow-lg shadow-admin-accent/20 focus:ring-admin-accent">
                        <span class="text-xs font-bold uppercase tracking-[0.2em]">Update Service Type</span>
                    </button>
                    <a href="{{ route('admin.service-types.index') }}" class="admin-action-btn admin-action-btn-ghost w-full justify-center py-3">
                        <span class="text-[10px] font-bold uppercase tracking-widest">Discard Changes</span>
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>

