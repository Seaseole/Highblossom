<x-layouts::admin title="Company Settings">
    <div class="max-w-5xl mx-auto space-y-10 py-10" x-data="{
        tab: '{{ request()->query('tab', 'general') }}',
        whatsappNumbers: {{ json_encode($settings['whatsapp_additional_numbers'] ?? []) }},
        workingHours: {{ json_encode($settings['working_hours'] ?? []) }},
        announcements: {{ json_encode($settings['announcements'] ?? []) }},
        init() {
            this.$watch('tab', value => {
                const url = new URL(window.location.href);
                url.searchParams.set('tab', value);
                window.history.replaceState({}, '', url.toString());
            });
        },
        addNumber() { this.whatsappNumbers.push({ label: '', number: '' }); },
        removeNumber(index) { this.whatsappNumbers.splice(index, 1); },
        addAnnouncement() { this.announcements.push({ text: '', link: '' }); },
        removeAnnouncement(index) { this.announcements.splice(index, 1); }
    }">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Company Settings</h1>
            <p class="text-gray-500 dark:text-gray-400">Manage your business information and dynamic variables.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" :value="tab">

            <!-- Tabs Navigation -->
            <div class="flex border-b border-gray-200 dark:border-white/10 space-x-1">
                @foreach([
                    'general' => 'General', 
                    'hours' => 'Hours', 
                    'assets' => 'Branding', 
                    'localization' => 'Locale', 
                    'social' => 'Social',
                    'notifications' => 'Notifications',
                    'announcements' => 'Announcements'
                ] as $key => $label)
                    <button type="button" 
                            @click="tab = '{{ $key }}'" 
                            :class="tab === '{{ $key }}' ? 'border-gray-900 dark:border-white text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'" 
                            class="pb-4 px-1 border-b-2 font-medium transition-colors text-sm">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Tab Contents -->
            <div class="space-y-8">
                <!-- General Tab -->
                <div x-show="tab === 'general'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">General Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Business Name</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name']) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Logo Text</label>
                            <input type="text" name="logo_text" value="{{ old('logo_text', $settings['logo_text']) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Primary Email</label>
                            <input type="email" name="primary_email" value="{{ old('primary_email', $settings['primary_email']) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Primary Phone</label>
                            <input type="text" name="primary_phone" value="{{ old('primary_phone', $settings['primary_phone']) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Business Address</label>
                            <textarea name="address" rows="3" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">{{ old('address', $settings['address']) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Business Hours Tab -->
                <div x-show="tab === 'hours'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm" style="display: none;">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Weekly Business Hours</h3>
                    <div class="space-y-4">
                        @php
                            $days = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
                        @endphp
                        @foreach($days as $key => $label)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5">
                                <div class="w-32">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="working_hours[{{ $key }}][is_closed]" value="1" {{ ($settings['working_hours'][$key]['is_closed'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                                    <span class="text-sm text-gray-500">Closed</span>
                                </div>
                                <div class="flex-1 flex gap-4">
                                    <input type="time" name="working_hours[{{ $key }}][open]" value="{{ $settings['working_hours'][$key]['open'] ?? '' }}" class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm outline-none">
                                    <input type="time" name="working_hours[{{ $key }}][close]" value="{{ $settings['working_hours'][$key]['close'] ?? '' }}" class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm outline-none">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Branding Tab -->
                <div x-show="tab === 'assets'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-8" style="display: none;"
                     x-data="{ 
                        logoPreview: '{{ $settings['business_logo'] ? Storage::url($settings['business_logo']) : null }}',
                        faviconPreview: '{{ $settings['favicon'] ? Storage::url($settings['favicon']) : null }}',
                        handleFileSelect(event, previewKey) {
                            const file = event.target.files[0];
                            if (file) this[previewKey] = URL.createObjectURL(file);
                        }
                     }">
                     <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Branding</h3>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                             <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Business Logo</label>
                             <div class="relative w-full bg-gray-50 dark:bg-white/5 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl flex flex-col items-center justify-center min-h-[160px] cursor-pointer hover:border-gray-900 dark:hover:border-white transition-all" @click="$refs.logoInput.click()">
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" class="max-h-[140px] object-contain">
                                </template>
                                <template x-if="!logoPreview">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Click to upload logo</span>
                                </template>
                             </div>
                             <input type="file" name="business_logo" x-ref="logoInput" class="hidden" accept="image/*" @change="handleFileSelect($event, 'logoPreview')">
                        </div>
                        <div class="space-y-4">
                             <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Favicon</label>
                             <div class="relative w-full bg-gray-50 dark:bg-white/5 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl flex flex-col items-center justify-center min-h-[160px] cursor-pointer hover:border-gray-900 dark:hover:border-white transition-all" @click="$refs.faviconInput.click()">
                                <template x-if="faviconPreview">
                                    <img :src="faviconPreview" class="max-h-[140px] object-contain">
                                </template>
                                <template x-if="!faviconPreview">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Click to upload favicon</span>
                                </template>
                             </div>
                             <input type="file" name="favicon" x-ref="faviconInput" class="hidden" accept="image/*" @change="handleFileSelect($event, 'faviconPreview')">
                        </div>
                     </div>
                </div>

                <!-- Locale Tab -->
                <div x-show="tab === 'localization'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6" style="display: none;">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Locale Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Timezone</label>
                            <input type="text" name="timezone" value="{{ old('timezone', $settings['timezone']) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Currency Symbol</label>
                            <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none">
                        </div>
                    </div>
                </div>

                <!-- Social Tab -->
                <div x-show="tab === 'social'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6" style="display: none;">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Social Links</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Facebook</label>
                            <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url']) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Instagram</label>
                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none">
                        </div>
                    </div>
                </div>

                <!-- Notifications Tab -->
                <div x-show="tab === 'notifications'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6" style="display: none;">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Quote Notification Emails</label>
                        <textarea name="quote_notification_emails" rows="3" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none">{{ (string) old('quote_notification_emails', $settings['quote_notification_emails'] ?? '') }}</textarea>
                    </div>
                </div>
                
                <!-- Announcements Tab -->
                <div x-show="tab === 'announcements'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6" style="display: none;">
                     <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Announcements</h3>
                     <template x-for="(announcement, index) in announcements" :key="index">
                        <div class="flex gap-4 p-4 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5">
                            <input type="text" :name="`announcements[${index}][text]`" x-model="announcement.text" class="flex-1 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm" placeholder="Text">
                            <input type="text" :name="`announcements[${index}][link]`" x-model="announcement.link" class="flex-1 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm" placeholder="Link">
                            <button type="button" @click="removeAnnouncement(index)" class="text-red-500">Remove</button>
                        </div>
                     </template>
                     <button type="button" @click="addAnnouncement()" class="text-sm text-gray-900 dark:text-white font-medium">+ Add Announcement</button>
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-white/10">
                    <button type="submit" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                        Save All Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>