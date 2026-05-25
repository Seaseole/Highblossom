<x-layouts::admin title="Gallery Settings">
    <div class="p-8 max-w-3xl mx-auto space-y-8" x-data="{
        metrics: {{ json_encode($settings['gallery_metrics']) }},
        addMetric() {
            if (this.metrics.length < 6) {
                this.metrics.push({ label: '', value: '', suffix: '' });
            }
        },
        removeMetric(index) {
            this.metrics.splice(index, 1);
        },
        moveUp(index) {
            if (index > 0) {
                this.metrics.splice(index - 1, 0, this.metrics.splice(index, 1)[0]);
            }
        },
        moveDown(index) {
            if (index < this.metrics.length - 1) {
                this.metrics.splice(index + 1, 0, this.metrics.splice(index, 1)[0]);
            }
        }
    }">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold tracking-tight text-admin-text font-headline">Gallery Settings</h1>
                <p class="text-admin-text-muted text-sm">Configure the performance metrics displayed on the gallery page.</p>
            </div>
            <a href="{{ route('admin.gallery.index') }}" class="admin-action-btn admin-action-btn-ghost">Back to Gallery</a>
        </div>

        <form action="{{ route('admin.gallery-settings.update') }}" method="POST" class="admin-glass-card p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <template x-for="(metric, index) in metrics" :key="index">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-4 bg-admin-surface rounded-xl border border-admin-border-subtle">
                        <div class="md:col-span-5">
                            <label class="block text-[10px] font-bold text-admin-text-muted uppercase tracking-widest mb-2">Label</label>
                            <input type="text" :name="`gallery_metrics[${index}][label]`" x-model="metric.label" class="w-full admin-input" required>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-bold text-admin-text-muted uppercase tracking-widest mb-2">Value</label>
                            <input type="text" :name="`gallery_metrics[${index}][value]`" x-model="metric.value" class="w-full admin-input" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-admin-text-muted uppercase tracking-widest mb-2">Suffix</label>
                            <input type="text" :name="`gallery_metrics[${index}][suffix]`" x-model="metric.suffix" class="w-full admin-input">
                        </div>
                        <div class="md:col-span-2 flex gap-2">
                            <button type="button" @click="moveUp(index)" class="p-2 text-admin-text-muted hover:text-admin-accent"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg></button>
                            <button type="button" @click="moveDown(index)" class="p-2 text-admin-text-muted hover:text-admin-accent"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></button>
                            <button type="button" @click="removeMetric(index)" class="p-2 text-admin-text-muted hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </div>
                    </div>
                </template>

                <button type="button" @click="addMetric()" x-show="metrics.length < 6" class="w-full py-4 border-2 border-dashed border-admin-border-subtle rounded-xl text-admin-text-muted hover:text-admin-accent hover:border-admin-accent transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Metric</span>
                </button>
            </div>

            <div class="pt-6 border-t border-admin-border-subtle flex justify-end">
                <button type="submit" class="admin-action-btn admin-action-btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</x-layouts::admin>