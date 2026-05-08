<x-layouts::admin title="Edit Glass Type">
    <div class="p-8 max-w-5xl mx-auto space-y-10">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3 text-admin-text-muted mb-2">
                    <a href="{{ route('admin.glass-types.index') }}" class="hover:text-admin-accent transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] font-body">Glass Types / Edit</span>
                </div>
                <h1 class="text-4xl font-bold tracking-tight text-admin-text font-headline leading-none">
                    Edit Glass Type
                </h1>
                <p class="text-admin-text-muted text-sm max-w-lg">
                    Modify the architectural glass variant details and visibility settings.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.glass-types.update', $glassType) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PUT')

            {{-- Main Form Area --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="admin-glass-card p-8 rounded-3xl shadow-2xl shadow-black/20 space-y-8">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $glassType->name) }}" required class="admin-form-input font-headline text-lg font-bold tracking-tight" placeholder="e.g. Laminated Structural Glass">
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
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $glassType->sort_order) }}" class="admin-form-input text-sm" placeholder="0">
                                @error('sort_order')
                                    <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="p-4 bg-admin-surface-alt/30 rounded-xl border border-admin-border-subtle group hover:border-admin-accent/20 transition-colors">
                                <label class="ui-checkbox-wrapper flex items-center gap-3 cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" class="ui-checkbox-input" {{ old('is_active', $glassType->is_active) ? 'checked' : '' }}>
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
                        <span class="text-xs font-bold uppercase tracking-[0.2em]">Update Glass Type</span>
                    </button>
                    <a href="{{ route('admin.glass-types.index') }}" class="admin-action-btn admin-action-btn-ghost w-full justify-center py-3">
                        <span class="text-[10px] font-bold uppercase tracking-widest">Discard Changes</span>
                    </a>
                </div>
            </div>
        </form>

        <!-- Sub-Categories Management -->
        <div class="mt-8 admin-glass-card rounded-2xl shadow-2xl shadow-black/20">
            <div class="px-8 py-6 border-b border-admin-border-subtle">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-admin-text font-headline">Sub-Categories</h3>
                        <p class="text-admin-text-muted text-sm mt-1">Manage detailed sub-categories for this glass type.</p>
                    </div>
                    <a href="{{ route('admin.glass-sub-categories.create') }}?glass_type_id={{ $glassType->id }}" class="admin-action-btn admin-action-btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Add Sub-Category</span>
                    </a>
                </div>
            </div>

            <div class="p-8">
                @if($glassSubCategories->count() > 0)
                    <div class="space-y-3">
                        @foreach($glassSubCategories as $subCategory)
                            <div class="flex items-center justify-between p-4 bg-admin-surface-alt rounded-xl border border-admin-border-subtle hover:border-admin-accent/30 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-admin-accent/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-admin-text">{{ $subCategory->name }}</h4>
                                        <p class="text-sm text-admin-text-muted font-mono">{{ $subCategory->slug }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-admin-text-muted">Order: {{ $subCategory->sort_order }}</span>
                                    @if($subCategory->is_active)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/30">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-admin-surface-alt text-admin-text-muted border border-admin-border-subtle">
                                            Inactive
                                        </span>
                                    @endif
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.glass-sub-categories.edit', $subCategory) }}" class="admin-action-btn admin-action-btn-ghost">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-admin-surface-alt flex items-center justify-center border border-admin-border-subtle mx-auto mb-4">
                            <svg class="w-8 h-8 text-admin-text-muted/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-admin-text mb-2">No Sub-Categories Yet</h3>
                        <p class="text-admin-text-muted text-sm mb-6">Add detailed sub-categories to help customers specify their exact glass needs.</p>
                        <a href="{{ route('admin.glass-sub-categories.create') }}?glass_type_id={{ $glassType->id }}" class="admin-action-btn admin-action-btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Create First Sub-Category</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::admin>
