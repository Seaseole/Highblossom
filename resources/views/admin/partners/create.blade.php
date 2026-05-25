<x-layouts::admin title="Create Partner">
    <div class="p-8 max-w-xl mx-auto">
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold tracking-tight text-admin-text font-headline mb-3">Add Partner</h1>
            <p class="text-admin-text-muted">Introduce a new trusted partner to the ecosystem.</p>
        </div>

        <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" 
              class="bg-admin-surface border border-admin-border-subtle rounded-3xl p-8 shadow-[0_20px_40px_-15px_rgba(0,0,0,0.05)] space-y-8">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-bold text-admin-text-muted uppercase tracking-widest mb-2">Partner Name</label>
                    <input type="text" name="name" 
                           class="w-full bg-admin-surface-alt border-admin-border-subtle rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#DC2626]/20 focus:border-[#DC2626] transition-all" 
                           placeholder="Enter partner name" required>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-admin-text-muted uppercase tracking-widest mb-2">Logo Upload</label>
                    <div class="group relative">
                        <input type="file" name="logo" 
                               class="w-full file:mr-4 file:py-2 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#DC2626] file:text-white hover:file:bg-[#B91C1C] transition-all bg-admin-surface-alt border border-admin-border-subtle rounded-xl p-2 cursor-pointer" required>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-admin-text-muted uppercase tracking-widest mb-2">Website URL</label>
                    <input type="url" name="website_url" 
                           class="w-full bg-admin-surface-alt border-admin-border-subtle rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#DC2626]/20 focus:border-[#DC2626] transition-all" 
                           placeholder="https://">
                </div>
            </div>

            <div class="pt-4 border-t border-admin-border-subtle flex justify-end gap-3">
                <a href="{{ route('admin.partners.index') }}" class="px-6 py-3 rounded-xl font-bold text-admin-text-muted hover:text-admin-text transition-colors">Cancel</a>
                <button type="submit" 
                        class="px-8 py-3 bg-[#DC2626] hover:bg-[#B91C1C] text-white font-bold rounded-xl shadow-lg shadow-[#DC2626]/20 transition-all active:scale-[0.98]">
                    Save Partner
                </button>
            </div>
        </form>
    </div>
</x-layouts::admin>