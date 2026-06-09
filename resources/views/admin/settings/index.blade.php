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
                        <div class="space-y-4" x-data="{ removeLogo: false }">
                             <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Business Logo</label>
                             <input type="hidden" name="remove_business_logo" :value="removeLogo ? 1 : 0">
                             <div class="relative w-full bg-gray-50 dark:bg-white/5 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl flex flex-col items-center justify-center min-h-[160px] cursor-pointer hover:border-gray-900 dark:hover:border-white transition-all" @click="if(!removeLogo) $refs.logoInput.click()">
                                <template x-if="logoPreview && !removeLogo">
                                    <img :src="logoPreview" class="max-h-[140px] object-contain">
                                </template>
                                <template x-if="!logoPreview || removeLogo">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Click to upload logo</span>
                                </template>
                             </div>
                             <input type="file" name="business_logo" x-ref="logoInput" class="hidden" accept="image/*" @change="handleFileSelect($event, 'logoPreview'); removeLogo = false;">
                             <button type="button" @click="removeLogo = true; logoPreview = null" x-show="logoPreview && !removeLogo" class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-6 rounded-full text-xs transition-all shadow-sm active:scale-[0.98]">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                     <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                 </svg>

                                 Remove Logo
                             </button>
                        </div>
                        <div class="space-y-4" x-data="{ removeFavicon: false }">
                             <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Favicon</label>
                             <input type="hidden" name="remove_favicon" :value="removeFavicon ? 1 : 0">
                             <div class="relative w-full bg-gray-50 dark:bg-white/5 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl flex flex-col items-center justify-center min-h-[160px] cursor-pointer hover:border-gray-900 dark:hover:border-white transition-all" @click="if(!removeFavicon) $refs.faviconInput.click()">
                                <template x-if="faviconPreview && !removeFavicon">
                                    <img :src="faviconPreview" class="max-h-[140px] object-contain">
                                </template>
                                <template x-if="!faviconPreview || removeFavicon">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Click to upload favicon</span>
                                </template>
                             </div>
                             <input type="file" name="favicon" x-ref="faviconInput" class="hidden" accept="image/*" @change="handleFileSelect($event, 'faviconPreview'); removeFavicon = false;">
                             <button type="button" @click="removeFavicon = true; faviconPreview = null" x-show="faviconPreview && !removeFavicon" class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-6 rounded-full text-xs transition-all shadow-sm active:scale-[0.98]">
                                <span class="material-symbols-outlined text-sm">&#xe872;</span>
                                Remove Favicon
                             </button>
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
                            <input type="text" name="timezone" value="{{ old('timezone', $settings['timezone']) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Currency Symbol</label>
                            <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Date Format</label>
                            <select name="date_format" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none">
                                @foreach(['d/m/Y', 'm/d/Y', 'Y-m-d', 'd.m.Y', 'j M Y', 'D, j M Y'] as $format)
                                    <option value="{{ $format }}" {{ old('date_format', $settings['date_format']) === $format ? 'selected' : '' }}>
                                        {{ date($format) }} ({{ $format }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Time Format</label>
                            <select name="time_format" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none">
                                @foreach(['H:i', 'h:i A', 'H:i:s', 'h:i:s A'] as $format)
                                    <option value="{{ $format }}" {{ old('time_format', $settings['time_format']) === $format ? 'selected' : '' }}>
                                        {{ date($format) }} ({{ $format }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Time Display</label>
                            <select name="time_format_display" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none">
                                <option value="12" {{ old('time_format_display', $settings['time_format_display']) === '12' ? 'selected' : '' }}>12-hour (AM/PM)</option>
                                <option value="24" {{ old('time_format_display', $settings['time_format_display']) === '24' ? 'selected' : '' }}>24-hour</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Locale</label>
                            <input type="text" name="locale" value="{{ old('locale', $settings['locale']) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none" placeholder="en_GB">
                        </div>
                    </div>
                </div>

                <!-- Social Tab -->
                <div x-show="tab === 'social'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-8" style="display: none;">

                    <div class="space-y-6">
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

                    <div class="pt-8 border-t border-gray-100 dark:border-white/10 space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">WhatsApp Settings</h3>
                            <button type="button" @click="addNumber()" class="text-sm text-gray-900 dark:text-white font-medium hover:underline">+ Add Additional Number</button>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Default WhatsApp Number</label>
                                <input type="text" name="whatsapp_number_default" value="{{ old('whatsapp_number_default', $settings['whatsapp_number_default']) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="+267 ...">
                            </div>

                            <div class="space-y-4">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Additional WhatsApp Numbers</label>
                                <div class="grid gap-4">
                                    <template x-for="(number, index) in whatsappNumbers" :key="index">
                                        <div class="flex gap-4 p-4 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5 items-start">
                                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="space-y-1">
                                                    <input type="text" :name="`whatsapp_additional_numbers[${index}][label]`" x-model="number.label" class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm focus:ring-1 focus:ring-gray-900 dark:focus:ring-white outline-none" placeholder="e.g. Sales, Support">
                                                </div>
                                                <div class="space-y-1">
                                                    <input type="text" :name="`whatsapp_additional_numbers[${index}][number]`" x-model="number.number" class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm focus:ring-1 focus:ring-gray-900 dark:focus:ring-white outline-none" placeholder="+267 ...">
                                                </div>
                                            </div>
                                            <button type="button" @click="removeNumber(index)" class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-6 rounded-full text-xs transition-all shadow-sm active:scale-[0.98] mt-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>

                                                Delete
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
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
                        <textarea name="quote_notification_emails" rows="3" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">{{ (string) old('quote_notification_emails', $settings['quote_notification_emails'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Announcements Tab -->
                <div x-show="tab === 'announcements'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-8" style="display: none;">

                     <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Announcements</h3>
                            <p class="text-sm text-gray-500">Manage the scrolling marquee messages.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="announcement_active" value="1" {{ $settings['announcement_active'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:bg-gray-400 peer-checked:bg-gray-900 dark:peer-checked:bg-white"></div>
                            <span class="ml-3 text-xs font-bold uppercase tracking-widest text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Active</span>
                        </label>
                     </div>

                     <div class="pt-8 border-t border-gray-100 dark:border-white/10 space-y-6">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400">Marquee Content</h4>
                            <button type="button" @click="addAnnouncement()" class="text-sm text-gray-900 dark:text-white font-medium hover:underline">+ Add Message</button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(announcement, index) in announcements" :key="index">
                                <div class="flex gap-4 p-6 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5 items-start">
                                    <div class="flex-1 space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="space-y-1.5">
                                                <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Message Text</label>
                                                <input type="text" :name="`announcements[${index}][text]`" x-model="announcement.text" class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:focus:ring-white" placeholder="Type message...">
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Destination</label>
                                                <select :name="`announcements[${index}][link]`" x-model="announcement.link" class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:focus:ring-white">
                                                    <option value="">No Link</option>
                                                    <optgroup label="System Routes">
                                                        @foreach($availableRoutes as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                    <template x-if="announcement.link && ![{{ implode(',', array_map(fn($r) => "'$r'", array_keys($availableRoutes))) }}].includes(announcement.link)">
                                                        <optgroup label="Custom Link">
                                                            <option :value="announcement.link" x-text="announcement.link" selected></option>
                                                        </optgroup>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" @click="removeAnnouncement(index)" class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-6 rounded-full text-xs transition-all shadow-sm active:scale-[0.98] mt-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>

                                        Delete
                                    </button>
                                </div>
                            </template>
                        </div>
                     </div>
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
