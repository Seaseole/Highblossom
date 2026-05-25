<x-layouts::admin title="Create Partner">
    <div class="p-8 max-w-xl mx-auto space-y-10">
        {{-- Header Section --}}
        <div class="space-y-1">
            <div class="flex items-center gap-3 text-admin-text-muted mb-2">
                <a href="{{ route('admin.partners.index') }}" class="hover:text-admin-accent transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <span class="text-[10px] font-bold uppercase tracking-[0.2em] font-body">Partners / New</span>
            </div>
            <h1 class="text-4xl font-bold tracking-tight text-admin-text font-headline leading-none">Add Partner</h1>
            <p class="text-admin-text-muted text-sm">Introduce a new trusted partner to the ecosystem.</p>
        </div>

        <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" 
              class="admin-glass-card p-8 rounded-3xl shadow-2xl shadow-black/20 space-y-8"
              x-data="{ imagePreview: null, handleFileSelect(event) { const file = event.target.files[0]; if (file) this.imagePreview = URL.createObjectURL(file); } }">
            @csrf

            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Partner Name</label>
                    <input type="text" name="name" class="admin-form-input text-lg font-bold" placeholder="e.g. Global Construct Corp" required>
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Partner Logo</label>
                    <div class="relative admin-form-input min-h-[160px] flex flex-col items-center justify-center border-dashed border-2 border-admin-border-subtle cursor-pointer hover:border-admin-accent/50 transition-colors" @click="$refs.logoInput.click()">
                        <template x-if="!imagePreview">
                            <div class="flex flex-col items-center text-center p-6">
                                <svg class="w-8 h-8 text-admin-text-muted mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Select Image</span>
                            </div>
                        </template>
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="max-h-[140px] object-contain">
                        </template>
                        <input type="file" name="logo" x-ref="logoInput" class="hidden" accept="image/*" @change="handleFileSelect" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Website URL</label>
                    <input type="url" name="website_url" class="admin-form-input text-sm" placeholder="https://">
                </div>
            </div>

            <div class="pt-6 border-t border-admin-border-subtle flex justify-end gap-3">
                <a href="{{ route('admin.partners.index') }}" class="px-6 py-3 rounded-xl font-bold text-admin-text-muted hover:text-admin-text transition-colors">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-[#DC2626] hover:bg-[#B91C1C] text-white font-bold rounded-xl shadow-lg shadow-[#DC2626]/20 transition-all active:scale-[0.98]">Save Partner</button>
            </div>
        </form>
    </div>
</x-layouts::admin>