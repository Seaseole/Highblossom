@props([
    'wireModel' => null,
    'fields' => [],
    'label' => 'Items',
])

<div
    x-data="{
        items: @entangle($wireModel),
        fieldDefs: {{ json_encode($fields) }},
        init() {
            if (!this.items || !Array.isArray(this.items)) {
                this.items = [];
            }
        },
        addItem() {
            const newItem = {};
            this.fieldDefs.forEach(field => {
                newItem[field.name] = field.type === 'image' ? null : '';
            });
            this.items.push(newItem);
        },
        removeItem(index) {
            this.items.splice(index, 1);
        },
        moveItem(index, direction) {
            if (direction === 'up' && index > 0) {
                [this.items[index], this.items[index - 1]] = [this.items[index - 1], this.items[index]];
            } else if (direction === 'down' && index < this.items.length - 1) {
                [this.items[index], this.items[index + 1]] = [this.items[index + 1], this.items[index]];
            }
        },
        updateField(index, fieldName, value) {
            this.items[index][fieldName] = value;
        },
        getFieldTypeIcon(type) {
            const icons = {
                text: 'type',
                textarea: 'align-left',
                image: 'image'
            };
            return icons[type] || 'type';
        }
    }"
    class="space-y-4"
>
    {{-- Items List --}}
    <div class="space-y-3">
        <template x-for="(item, index) in items" :key="index">
            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                {{-- Item Header --}}
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-[#FAFAFA]" x-text="'Item #' + (index + 1)"></span>
                    <div class="flex items-center gap-1">
                        <button
                            type="button"
                            @click="moveItem(index, 'up')"
                            :disabled="index === 0"
                            class="p-1.5 rounded-lg hover:bg-white/10 text-[#A1A1AA] disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
                            title="Move Up"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                        <button
                            type="button"
                            @click="moveItem(index, 'down')"
                            :disabled="index === items.length - 1"
                            class="p-1.5 rounded-lg hover:bg-white/10 text-[#A1A1AA] disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
                            title="Move Down"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <button
                            type="button"
                            @click="removeItem(index)"
                            class="p-1.5 rounded-lg hover:bg-red-500/20 text-[#A1A1AA] hover:text-red-400 transition-colors"
                            title="Remove Item"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Fields --}}
                <div class="space-y-3">
                    <template x-for="fieldDef in fieldDefs" :key="fieldDef.name">
                        <div>
                            <label class="block text-xs font-medium text-[#A1A1AA] mb-1.5" x-text="fieldDef.label"></label>

                            {{-- Text Field --}}
                            <template x-if="fieldDef.type === 'text'">
                                <input
                                    type="text"
                                    x-model="item[fieldDef.name]"
                                    class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200 text-sm"
                                    :placeholder="fieldDef.label"
                                >
                            </template>

                            {{-- Textarea Field --}}
                            <template x-if="fieldDef.type === 'textarea'">
                                <textarea
                                    x-model="item[fieldDef.name]"
                                    rows="3"
                                    class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200 resize-none text-sm"
                                    :placeholder="fieldDef.label"
                                ></textarea>
                            </template>

                            {{-- Image Field --}}
                            <template x-if="fieldDef.type === 'image'">
                                <div class="space-y-2">
                                    <div x-show="item[fieldDef.name]" class="relative">
                                        <img :src="item[fieldDef.name]" class="w-full h-32 object-cover rounded-lg border border-white/10">
                                        <button
                                            type="button"
                                            @click="item[fieldDef.name] = null"
                                            class="absolute top-2 right-2 p-1.5 rounded-lg bg-[#0A0A0F]/80 backdrop-blur-sm hover:bg-[#DC2626] transition-colors"
                                        >
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <input
                                        type="text"
                                        x-model="item[fieldDef.name]"
                                        class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200 text-sm"
                                        placeholder="Image URL"
                                    >
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>

    {{-- Add Button --}}
    <button
        type="button"
        @click="addItem"
        class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-white/5 border border-white/10 border-dashed text-[#A1A1AA] hover:bg-white/10 hover:border-[#DC2626]/30 hover:text-[#FAFAFA] transition-all duration-200"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="text-sm font-medium">Add {{ rtrim($label, 's') }}</span>
    </button>

    {{-- Empty State --}}
    <div x-show="items.length === 0" class="text-center py-6 text-[#71717A]">
        <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        <p class="text-sm">No {{ strtolower($label) }} yet. Click above to add.</p>
    </div>
</div>
