<x-layouts::admin title="Gallery Settings">
    <div
        class="mx-auto max-w-3xl space-y-8 p-8"
        x-data="{
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
    }"
    >
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-admin-text font-headline text-3xl font-bold tracking-tight">Gallery Settings</h1>
                <p class="text-admin-text-muted text-sm">
                    Configure the performance metrics displayed on the gallery page.
                </p>
            </div>
            <a href="{{ route('admin.gallery.index') }}" class="admin-action-btn admin-action-btn-ghost"
                >Back to Gallery</a>
        </div>

        <form
            action="{{ route('admin.gallery-settings.update') }}"
            method="POST"
            class="admin-glass-card space-y-6 p-8"
        >
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <template x-for="(metric, index) in metrics" :key="index">
                    <div class="bg-admin-surface border-admin-border-subtle grid grid-cols-1 items-end gap-4 rounded-xl border p-4 md:grid-cols-12">
                        <div class="md:col-span-5">
                            <label class="text-admin-text-muted mb-2 block text-[10px] font-bold tracking-widest uppercase">Label</label>
                            <input
                                type="text"
                                :name="`gallery_metrics[${index}][label]`"
                                x-model="metric.label"
                                class="admin-input w-full"
                                required
                            />
                        </div>
                        <div class="md:col-span-3">
                            <label class="text-admin-text-muted mb-2 block text-[10px] font-bold tracking-widest uppercase">Value</label>
                            <input
                                type="text"
                                :name="`gallery_metrics[${index}][value]`"
                                x-model="metric.value"
                                class="admin-input w-full"
                                required
                            />
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-admin-text-muted mb-2 block text-[10px] font-bold tracking-widest uppercase">Suffix</label>
                            <input
                                type="text"
                                :name="`gallery_metrics[${index}][suffix]`"
                                x-model="metric.suffix"
                                class="admin-input w-full"
                            />
                        </div>
                        <div class="flex gap-2 md:col-span-2">
                            <button
                                type="button"
                                @click="moveUp(index)"
                                class="text-admin-text-muted hover:text-admin-accent p-2"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                            </button>
                            <button
                                type="button"
                                @click="moveDown(index)"
                                class="text-admin-text-muted hover:text-admin-accent p-2"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <button
                                type="button"
                                @click="removeMetric(index)"
                                class="text-admin-text-muted p-2 hover:text-red-500"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                </template>

                <button
                    type="button"
                    @click="addMetric()"
                    x-show="metrics.length < 6"
                    class="border-admin-border-subtle text-admin-text-muted hover:text-admin-accent hover:border-admin-accent flex w-full items-center justify-center gap-2 rounded-xl border-2 border-dashed py-4 transition-colors"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Add Metric</span>
                </button>
            </div>

            <div class="border-admin-border-subtle flex justify-end border-t pt-6">
                <button type="submit" class="admin-action-btn admin-action-btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</x-layouts::admin>
