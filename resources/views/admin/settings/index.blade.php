<x-layouts::admin title="Company Settings">
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#FAFAFA] font-headline">Company Settings</h1>
                <p class="text-[#A1A1AA] mt-1 text-sm">Manage your business information and dynamic variables.</p>
            </div>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{
            tab: 'general',
            whatsappNumbers: {{ json_encode($settings['whatsapp_additional_numbers'] ?? []) }},
            workingHours: {{ json_encode($settings['working_hours'] ?? []) }},
            addNumber() {
                this.whatsappNumbers.push({ label: '', number: '' });
            },
            removeNumber(index) {
                this.whatsappNumbers.splice(index, 1);
            }
        }">
            @csrf
            @method('PUT')

            <!-- Tabs Navigation -->
            <div class="flex border-b border-white/5 space-x-8">
                <button type="button" @click="tab = 'general'" :class="tab === 'general' ? 'border-[#DC2626] text-[#FAFAFA]' : 'border-transparent text-[#A1A1AA] hover:text-[#FAFAFA]'" class="pb-4 border-b-2 font-medium transition-all duration-300">
                    General Info
                </button>
                <button type="button" @click="tab = 'hours'" :class="tab === 'hours' ? 'border-[#DC2626] text-[#FAFAFA]' : 'border-transparent text-[#A1A1AA] hover:text-[#FAFAFA]'" class="pb-4 border-b-2 font-medium transition-all duration-300">
                    Business Hours
                </button>
                <button type="button" @click="tab = 'assets'" :class="tab === 'assets' ? 'border-[#DC2626] text-[#FAFAFA]' : 'border-transparent text-[#A1A1AA] hover:text-[#FAFAFA]'" class="pb-4 border-b-2 font-medium transition-all duration-300">
                    Assets & Branding
                </button>
                <button type="button" @click="tab = 'localization'" :class="tab === 'localization' ? 'border-[#DC2626] text-[#FAFAFA]' : 'border-transparent text-[#A1A1AA] hover:text-[#FAFAFA]'" class="pb-4 border-b-2 font-medium transition-all duration-300">
                    Localization
                </button>
                <button type="button" @click="tab = 'social'" :class="tab === 'social' ? 'border-[#DC2626] text-[#FAFAFA]' : 'border-transparent text-[#A1A1AA] hover:text-[#FAFAFA]'" class="pb-4 border-b-2 font-medium transition-all duration-300">
                    Social & WhatsApp
                </button>
            </div>

            <!-- Tab Contents -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <!-- General Tab -->
                    <div x-show="tab === 'general'" class="space-y-6">
                        <div class="bg-[#16161d] rounded-2xl border border-white/5 p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Business Name</label>
                                    <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Logo Text</label>
                                    <input type="text" name="logo_text" value="{{ old('logo_text', $settings['logo_text']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Primary Email</label>
                                    <input type="email" name="primary_email" value="{{ old('primary_email', $settings['primary_email']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Primary Phone</label>
                                    <input type="text" name="primary_phone" value="{{ old('primary_phone', $settings['primary_phone']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Business Address</label>
                                    <textarea name="address" rows="3" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">{{ old('address', $settings['address']) }}</textarea>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Google Maps API Key</label>
                                    <input type="text" name="google_maps_api_key" value="{{ old('google_maps_api_key', $settings['google_maps_api_key']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                    <p class="text-[10px] text-[#71717A]">Required for map iframe embed. Get from Google Cloud Console.</p>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Google Maps Directions Link</label>
                                    <input type="url" name="map_directions_link" value="{{ old('map_directions_link', $settings['map_directions_link']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                    <p class="text-[10px] text-[#71717A]">Share link from Google Maps (e.g., https://maps.app.goo.gl/xxxxx)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Business Hours Tab -->
                    <div x-show="tab === 'hours'" class="space-y-6" style="display: none;">
                        <div class="bg-[#16161d] rounded-2xl border border-white/5 p-6 space-y-6">
                            <h3 class="text-lg font-bold text-[#FAFAFA]">Weekly Business Hours</h3>
                            <div class="space-y-4">
                                @php
                                    $days = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
                                @endphp
                                @foreach($days as $key => $label)
                                    <div class="flex items-center gap-4 p-4 bg-black/20 rounded-xl">
                                        <div class="w-32">
                                            <label class="text-sm font-medium text-[#FAFAFA]">{{ $label }}</label>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox"
                                                name="working_hours[{{ $key }}][is_closed]"
                                                x-model="workingHours.{{ $key }}.is_closed"
                                                value="1"
                                                {{ ($settings['working_hours'][$key]['is_closed'] ?? false) ? 'checked' : '' }}
                                                class="w-4 h-4 rounded border-white/20 bg-black/30 text-[#DC2626] focus:ring-[#DC2626]">
                                            <span class="text-sm text-[#71717A]">Closed</span>
                                        </div>
                                        <div class="flex-1 flex gap-4">
                                            <div class="flex-1 space-y-1">
                                                <label class="text-xs text-[#71717A]">Open</label>
                                                <input type="time"
                                                    :name="`working_hours[{{ $key }}][open]`"
                                                    x-model="workingHours.{{ $key }}.open"
                                                    value="{{ $settings['working_hours'][$key]['open'] ?? '' }}"
                                                    :disabled="workingHours.{{ $key }}.is_closed"
                                                    class="w-full bg-black/20 border border-white/5 rounded-lg px-3 py-2 text-sm text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all disabled:opacity-50">
                                            </div>
                                            <div class="flex-1 space-y-1">
                                                <label class="text-xs text-[#71717A]">Close</label>
                                                <input type="time"
                                                    :name="`working_hours[{{ $key }}][close]`"
                                                    x-model="workingHours.{{ $key }}.close"
                                                    value="{{ $settings['working_hours'][$key]['close'] ?? '' }}"
                                                    :disabled="workingHours.{{ $key }}.is_closed"
                                                    class="w-full bg-black/20 border border-white/5 rounded-lg px-3 py-2 text-sm text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all disabled:opacity-50">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Assets Tab -->
                    <div x-show="tab === 'assets'" class="space-y-6" style="display: none;">
                        <div class="bg-[#16161d] rounded-2xl border border-white/5 p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Business Logo</label>
                                    <input type="hidden" name="business_logo_path" id="business-logo-path" value="{{ $settings['business_logo'] ?? '' }}">
                                    <div id="business-logo-progress" class="mb-3"></div>
                                    <div class="flex flex-col items-center justify-center p-8 bg-black/20 border-2 border-dashed border-white/5 rounded-2xl group hover:border-[#DC2626]/50 transition-all cursor-pointer relative overflow-hidden">
                                        @if($settings['business_logo'])
                                            <img src="{{ Storage::url($settings['business_logo']) }}" class="h-20 object-contain mb-4">
                                        @else
                                            <svg class="w-10 h-10 text-[#71717A] mb-4 group-hover:text-[#DC2626] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                        <p class="text-xs text-[#71717A]">Click to upload or drag and drop</p>
                                        <input type="file" name="business_logo" id="business-logo-input" class="absolute inset-0 opacity-0 cursor-pointer">
                                    </div>
                                    <p class="text-[10px] text-[#71717A]">Recommended: PNG or SVG, max 2MB.</p>
                                </div>
                                <div class="space-y-4">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Favicon</label>
                                    <input type="hidden" name="favicon_path" id="favicon-path" value="{{ $settings['favicon'] ?? '' }}">
                                    <div id="favicon-progress" class="mb-3"></div>
                                    <div class="flex flex-col items-center justify-center p-8 bg-black/20 border-2 border-dashed border-white/5 rounded-2xl group hover:border-[#DC2626]/50 transition-all cursor-pointer relative overflow-hidden">
                                        @if($settings['favicon'])
                                            <img src="{{ Storage::url($settings['favicon']) }}" class="h-10 object-contain mb-4">
                                        @else
                                            <svg class="w-10 h-10 text-[#71717A] mb-4 group-hover:text-[#DC2626] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.643-1.643a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-1.643 1.643M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        @endif
                                        <p class="text-xs text-[#71717A]">Click to upload favicon</p>
                                        <input type="file" name="favicon" id="favicon-input" class="absolute inset-0 opacity-0 cursor-pointer">
                                    </div>
                                    <p class="text-[10px] text-[#71717A]">Recommended: ICO or PNG, max 1MB.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Localization Tab -->
                    <div x-show="tab === 'localization'" class="space-y-6" style="display: none;">
                        <div class="bg-[#16161d] rounded-2xl border border-white/5 p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Timezone</label>
                                    <input type="text" name="timezone" value="{{ old('timezone', $settings['timezone']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Locale</label>
                                    <input type="text" name="locale" value="{{ old('locale', $settings['locale']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Date Format</label>
                                    <input type="text" name="date_format" value="{{ old('date_format', $settings['date_format']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                    <p class="text-[10px] text-[#71717A]">Example: d/m/Y, M j, Y</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Time Format</label>
                                    <input type="text" name="time_format" value="{{ old('time_format', $settings['time_format']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                    <p class="text-[10px] text-[#71717A]">Example: H:i, h:i A</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Time Display Format (Public)</label>
                                    <select name="time_format_display" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                        <option value="12" {{ old('time_format_display', $settings['time_format_display']) == '12' ? 'selected' : '' }}>12-hour (8:00 AM - 5:30 PM)</option>
                                        <option value="24" {{ old('time_format_display', $settings['time_format_display']) == '24' ? 'selected' : '' }}>24-hour (08:00 - 17:30)</option>
                                    </select>
                                    <p class="text-[10px] text-[#71717A]">Format for displaying working hours on public pages</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Currency Symbol</label>
                                    <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Tab -->
                    <div x-show="tab === 'social'" class="space-y-6" style="display: none;">
                        <div class="bg-[#16161d] rounded-2xl border border-white/5 p-6 space-y-6">
                            <h3 class="text-lg font-bold text-[#FAFAFA]">WhatsApp Numbers</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Default WhatsApp</label>
                                    <input type="text" name="whatsapp_number_default" value="{{ old('whatsapp_number_default', $settings['whatsapp_number_default']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                </div>
                                
                                <div class="space-y-4 pt-4 border-t border-white/5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-sm font-medium text-[#FAFAFA]">Additional Numbers</label>
                                        <button type="button" @click="addNumber()" class="text-xs text-[#DC2626] hover:underline">+ Add Number</button>
                                    </div>
                                    <template x-for="(item, index) in whatsappNumbers" :key="index">
                                        <div class="flex gap-4 items-start">
                                            <div class="flex-1 space-y-1">
                                                <input type="text" :name="`whatsapp_additional_numbers[${index}][label]`" x-model="item.label" placeholder="Label (e.g. Sales)" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-2 text-sm text-[#FAFAFA] focus:border-[#DC2626] transition-all">
                                            </div>
                                            <div class="flex-1 space-y-1">
                                                <input type="text" :name="`whatsapp_additional_numbers[${index}][number]`" x-model="item.number" placeholder="Phone Number" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-2 text-sm text-[#FAFAFA] focus:border-[#DC2626] transition-all">
                                            </div>
                                            <button type="button" @click="removeNumber(index)" class="p-2 text-[#A1A1AA] hover:text-[#DC2626] transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <h3 class="text-lg font-bold text-[#FAFAFA] pt-8 border-t border-white/5">Social Media Links</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Facebook URL</label>
                                    <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">Instagram URL</label>
                                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-[#FAFAFA]">LinkedIn URL</label>
                                    <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $settings['linkedin_url']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Sidebar -->
                <div class="space-y-6">
                    <div class="bg-[#16161d] rounded-2xl border border-white/5 p-6 space-y-4">
                        <h3 class="font-bold text-[#FAFAFA]">Deployment Info</h3>
                        <p class="text-xs text-[#A1A1AA] leading-relaxed">
                            Changes saved here will reflect immediately across the public website. Some changes might require a browser refresh due to server-side caching.
                        </p>
                        <div class="pt-4 border-t border-white/5">
                            <button type="submit" class="w-full py-4 bg-[#DC2626] hover:bg-[#B91C1C] text-white font-bold rounded-xl shadow-lg shadow-[#DC2626]/20 transition-all active:scale-[0.98]">
                                Save All Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/image-upload.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof ImageUploader !== 'undefined') {
                // Business Logo Uploader
                new ImageUploader({
                    fileInput: document.getElementById('business-logo-input'),
                    previewContainer: document.querySelector('[x-show="tab === \'assets\'"]'),
                    progressContainer: document.getElementById('business-logo-progress'),
                    hiddenInput: document.getElementById('business-logo-path'),
                    uploadUrl: '{{ route("admin.image-upload") }}',
                    csrfToken: '{{ csrf_token() }}',
                    maxSize: 2 * 1024 * 1024, // 2MB
                    acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/svg+xml'],
                    onUploadComplete: function(response) {
                        console.log('Logo uploaded successfully:', response);
                    },
                    onUploadError: function(message) {
                        console.error('Upload error:', message);
                    }
                });

                // Favicon Uploader
                new ImageUploader({
                    fileInput: document.getElementById('favicon-input'),
                    previewContainer: document.querySelector('[x-show="tab === \'assets\'"]'),
                    progressContainer: document.getElementById('favicon-progress'),
                    hiddenInput: document.getElementById('favicon-path'),
                    uploadUrl: '{{ route("admin.image-upload") }}',
                    csrfToken: '{{ csrf_token() }}',
                    maxSize: 1 * 1024 * 1024, // 1MB
                    acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon'],
                    onUploadComplete: function(response) {
                        console.log('Favicon uploaded successfully:', response);
                    },
                    onUploadError: function(message) {
                        console.error('Upload error:', message);
                    }
                });
            }
        });
    </script>
</x-layouts::admin>
