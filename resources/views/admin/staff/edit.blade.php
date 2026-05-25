<x-layouts::admin title="Edit Staff Member">
    <div class="p-8 max-w-xl mx-auto space-y-10">
        {{-- Header Section --}}
        <div class="space-y-1">
            <div class="flex items-center gap-3 text-admin-text-muted mb-2">
                <a href="{{ route('admin.staff.index') }}" class="hover:text-admin-accent transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <span class="text-[10px] font-bold uppercase tracking-[0.2em] font-body">Staff / Edit</span>
            </div>
            <h1 class="text-4xl font-bold tracking-tight text-admin-text font-headline leading-none">Edit Staff Member</h1>
            <p class="text-admin-text-muted text-sm">Update staff member information.</p>
        </div>

        <form action="{{ route('admin.staff.update', $staff) }}" method="POST" enctype="multipart/form-data" 
              class="admin-glass-card p-8 rounded-3xl shadow-2xl shadow-black/20 space-y-8"
              x-data="{ imagePreview: '{{ $staff->photo_url }}', handleFileSelect(event) { const file = event.target.files[0]; if (file) this.imagePreview = URL.createObjectURL(file); } }">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $staff->name) }}" class="admin-form-input text-lg font-bold" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Role</label>
                    <input type="text" name="role" value="{{ old('role', $staff->role) }}" class="admin-form-input text-sm" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Bio</label>
                    <textarea name="bio" rows="4" class="admin-form-input text-sm">{{ old('bio', $staff->bio) }}</textarea>
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Photo</label>
                    <div class="relative admin-form-input min-h-[160px] flex flex-col items-center justify-center border-dashed border-2 border-admin-border-subtle cursor-pointer hover:border-admin-accent/50 transition-colors" @click="$refs.photoInput.click()">
                        <img :src="imagePreview" class="max-h-[140px] object-cover rounded-full">
                        <input type="file" name="photo" x-ref="photoInput" class="hidden" accept="image/*" @change="handleFileSelect">
                    </div>
                    <p class="text-[10px] text-admin-text-muted">Click the photo to replace.</p>
                </div>
            </div>

            <div class="pt-6 border-t border-admin-border-subtle flex justify-end gap-3">
                <a href="{{ route('admin.staff.index') }}" class="px-6 py-3 rounded-xl font-bold text-admin-text-muted hover:text-admin-text transition-colors">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-[#DC2626] hover:bg-[#B91C1C] text-white font-bold rounded-xl shadow-lg shadow-[#DC2626]/20 transition-all active:scale-[0.98]">Update Member</button>
            </div>
        </form>
    </div>
</x-layouts::admin>