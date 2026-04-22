<x-layouts::site title="Request a Quote">
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">{{ __('quote.label') }}</div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                    {{ __('quote.title') }}
                </h1>
                <p class="text-lg text-[#A1A1AA] leading-relaxed">
                    {{ __('quote.description') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Quote Form Section -->
    <section class="py-24 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-12">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class="glass-card rounded-2xl p-8 md:p-12">
                        <form action="{{ route('quote.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                            @csrf

                            <!-- Personal Information -->
                            <div>
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline">{{ __('quote.your_information') }}</h3>
                                        <p class="text-[#71717A] text-sm">{{ __('quote.your_info_description') }}</p>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.full_name_label') }} *</label>
                                        <input type="text" id="name" name="name" required
                                            class="form-input-premium"
                                            placeholder="John Doe">
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.phone_number_label') }} *</label>
                                        <input type="tel" id="phone" name="phone" required
                                            class="form-input-premium"
                                            placeholder="+267 XX XXX XXX">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="email" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.email_address_label') }}</label>
                                        <input type="email" id="email" name="email"
                                            class="form-input-premium"
                                            placeholder="john@example.com">
                                    </div>
                                </div>
                            </div>

                            <!-- Vehicle Information -->
                            <div class="pt-10 border-t border-white/5">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline">{{ __('quote.vehicle_details') }}</h3>
                                        <p class="text-[#71717A] text-sm">{{ __('quote.vehicle_info_description') }}</p>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="vehicle_type" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.vehicle_type') }} *</label>
                                        <select id="vehicle_type" name="vehicle_type" required class="form-input-premium">
                                            <option value="">{{ __('quote.select_type') }}</option>
                                            <option value="sedan">Sedan / Hatchback</option>
                                            <option value="suv">SUV / 4x4</option>
                                            <option value="truck">Truck / Bakkie</option>
                                            <option value="van">Van / Minibus</option>
                                            <option value="heavy">Heavy Machinery</option>
                                            <option value="fleet">Fleet Vehicle</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="make_model" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.make_model') }}</label>
                                        <input type="text" id="make_model" name="make_model"
                                            class="form-input-premium"
                                            placeholder="e.g., Toyota Hilux 2020">
                                    </div>
                                    <div>
                                        <label for="reg_number" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.registration_number') }}</label>
                                        <input type="text" id="reg_number" name="reg_number"
                                            class="form-input-premium"
                                            placeholder="e.g., B 123 ABC">
                                    </div>
                                    <div>
                                        <label for="year" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.vehicle_year') }}</label>
                                        <input type="number" id="year" name="year" min="1980" max="2026"
                                            class="form-input-premium"
                                            placeholder="2020">
                                    </div>
                                </div>
                            </div>

                            <!-- Service Required -->
                            <div class="pt-10 border-t border-white/5">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline">{{ __('quote.service_required') }}</h3>
                                        <p class="text-[#71717A] text-sm">{{ __('quote.service_description') }}</p>
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="glass_type_id" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.glass_type') }} *</label>
                                        <select id="glass_type_id" name="glass_type_id" required class="form-input-premium">
                                            <option value="">{{ __('quote.select_glass_type') }}</option>
                                            @foreach($glassTypes as $glassType)
                                            <option value="{{ $glassType->id }}">{{ $glassType->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="service_type_id" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.service_type') }} *</label>
                                        <select id="service_type_id" name="service_type_id" required class="form-input-premium">
                                            <option value="">{{ __('quote.select_service') }}</option>
                                            @foreach($serviceTypes as $serviceType)
                                            <option value="{{ $serviceType->id }}">{{ $serviceType->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="pt-10 border-t border-white/5">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline">{{ __('quote.additional_info') }}</h3>
                                        <p class="text-[#71717A] text-sm">{{ __('quote.additional_info_description') }}</p>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-[#FAFAFA] mb-2">{{ __('quote.visual_assessment') }}</h3>
                                    <p class="text-sm text-[#71717A] mb-4">{{ __('quote.upload_description') }}</p>
                                    
                                    <input type="hidden" name="image_path" id="quote-image-path">
                                    
                                    <div id="quote-image-preview" class="mb-4"></div>
                                    <div id="quote-image-progress"></div>
                                    
                                    <div class="mt-4 flex justify-center rounded-lg border border-dashed border-white/20 px-6 py-10 bg-white/[0.02] hover:bg-white/[0.04] transition-colors">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-[#DC2626]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.69a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                            </svg>
                                            <div class="mt-4 flex text-sm leading-6 text-[#A1A1AA]">
                                                <label for="file-upload" class="relative cursor-pointer rounded-md font-semibold text-[#DC2626] focus-within:outline-none focus-within:ring-2 focus-within:ring-[#DC2626] focus-within:ring-offset-2 hover:text-[#EF4444]">
                                                    <span>{{ __('quote.click_to_upload') }}</span>
                                                    <input id="file-upload" name="image" type="file" accept="image/*" class="sr-only">
                                                </label>
                                            </div>
                                            <p class="text-xs leading-5 text-[#71717A]">{{ __('quote.file_requirements') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="mobile_service" value="1"
                                        class="w-5 h-5 rounded border border-white/20 bg-white/5 text-[#DC2626] focus:ring-[#DC2626] focus:ring-offset-0 focus:ring-offset-gray-900 transition-all cursor-pointer accent-[#DC2626]">
                                    <span class="text-[#A1A1AA] text-sm group-hover:text-[#FAFAFA] transition-colors">{{ __('quote.mobile_service') }}</span>
                                </label>
                            </div>

                            <!-- Submit -->
                            <div class="pt-10 border-t border-white/5">
                                <button type="submit" class="btn-premium glow-red-subtle w-full md:w-auto text-lg px-12 py-4">
                                    <span>{{ __('quote.submit_quote') }}</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                                <p class="text-[#71717A] text-sm mt-4">
                                    {{ __('quote.submit_disclaimer') }}
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-32 space-y-6">
                        <!-- Why Request Quote -->
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-[#FAFAFA] font-headline mb-4">{{ __('quote.why_quote_title') }}</h3>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-[#DC2626] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-[#A1A1AA] text-sm">{{ __('quote.benefit_1') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-[#DC2626] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-[#A1A1AA] text-sm">{{ __('quote.benefit_2') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-[#DC2626] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-[#A1A1AA] text-sm">{{ __('quote.benefit_3') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-[#DC2626] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-[#A1A1AA] text-sm">{{ __('quote.benefit_4') }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Contact Card -->
                        @if($primaryPhone)
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-[#FAFAFA] font-headline mb-4">{{ __('quote.prefer_to_call') }}</h3>
                            <p class="text-[#A1A1AA] text-sm mb-4">{{ __('quote.call_description') }}</p>
                            <a href="tel:{{ str_replace([' ', '-', '(', ')'], '', $primaryPhone) }}" class="flex items-center gap-3 text-[#DC2626] font-semibold hover:underline">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $primaryPhone }}
                            </a>
                        </div>
                        @endif

                        <!-- Emergency Banner -->
                        <div class="bg-[#DC2626]/10 border border-[#DC2626]/30 rounded-2xl p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="w-2 h-2 bg-[#DC2626] rounded-full animate-pulse"></span>
                                <span class="text-[#DC2626] text-sm font-semibold uppercase">{{ __('quote.emergency_24_7') }}</span>
                            </div>
                            <p class="text-[#A1A1AA] text-sm mb-4">
                                {{ __('quote.emergency_description') }}
                            </p>
                            @if($primaryPhone)
                            <a href="tel:{{ str_replace([' ', '-', '(', ')'], '', $primaryPhone) }}" class="btn-premium w-full text-sm py-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>{{ __('quote.call_emergency') }}</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts::site>

<script src="{{ asset('js/image-upload.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof ImageUploader !== 'undefined') {
            new ImageUploader({
                fileInput: document.querySelector('input[name="image"]'),
                previewContainer: document.getElementById('quote-image-preview'),
                progressContainer: document.getElementById('quote-image-progress'),
                hiddenInput: document.getElementById('quote-image-path'),
                uploadUrl: '{{ route("admin.image-upload") }}',
                csrfToken: '{{ csrf_token() }}',
                maxSize: 2 * 1024 * 1024, // 2MB
                acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
                onUploadComplete: function(response) {
                    console.log('Image uploaded successfully:', response);
                },
                onUploadError: function(message) {
                    console.error('Upload error:', message);
                }
            });
        }
    });
</script>
