<x-layouts::admin title="Edit Partner">
    <div class="p-8 max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold tracking-tight text-admin-text font-headline">Edit Partner</h1>
                <p class="text-admin-text-muted text-sm">Update partner logo and information.</p>
            </div>
            <a href="{{ route('admin.partners.index') }}" class="admin-action-btn admin-action-btn-ghost">Back to Partners</a>
        </div>

        <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data" class="admin-glass-card p-8 space-y-8">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-admin-text-muted uppercase tracking-widest mb-2">Partner Name</label>
                    <input type="text" name="name" value="{{ old('name', $partner->name) }}" class="w-full admin-input" required>
                </div>

                <div class="flex items-center gap-6">
                    <img src="{{ $partner->logo_url }}" class="w-24 h-16 object-contain p-2 bg-admin-surface-alt rounded-xl border border-admin-border-subtle">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-admin-text-muted uppercase tracking-widest mb-2">Replace Logo (Optional)</label>
                        <input type="file" name="logo" class="w-full text-sm text-admin-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-admin-surface-alt file:text-admin-text hover:file:bg-admin-surface transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-admin-text-muted uppercase tracking-widest mb-2">Website URL (Optional)</label>
                    <input type="url" name="website_url" value="{{ old('website_url', $partner->website_url) }}" class="w-full admin-input" placeholder="https://">
                </div>
            </div>

            <div class="pt-6 border-t border-admin-border-subtle flex justify-end">
                <button type="submit" class="admin-action-btn admin-action-btn-primary px-8">Update Partner</button>
            </div>
        </form>
    </div>
</x-layouts::admin>