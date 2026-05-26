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
                        <form action="{{ route('quote.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-10"
                            x-data="{ 
                                isSubmitting: false, 
                                isUploading: false, 
                                hasImage: false,
                                showErrors: true,
                                init() {
                                    setTimeout(() => this.showErrors = false, 5000);
                                }
                            }"
                            @submit.prevent="if (!isSubmitting && !isUploading) { isSubmitting = true; $el.submit(); }">
                            @csrf
                            <input type="hidden" name="_idempotency_token" value="{{ session()->get('quote_token', md5(uniqid())) }}">
                            @php(session()->put('quote_token', md5(uniqid())))

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
                                        <label for="name" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.full_name_label') }} <span style="color: red">*</span></label>
                                        <input type="text" id="name" name="name" required
                                            class="form-input-premium @error('name') border-red-500 @enderror"
                                            placeholder="John Doe"
                                            oninput=this.value=this.value.replace(/[^a-zA-Z ]/g,'')">
                                        @error('name')
                                            <p x-show="showErrors" class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.phone_number_label') }} <span style="color: red">*</span></label>
                                        <input type="tel" id="phone" name="phone" required
                                            class="form-input-premium @error('phone') border-red-500 @enderror"
                                            placeholder="267 XX XXX XXX"
                                            oninput=this.value=this.value.replace(/[^0-9]/g,'')>
                                        @error('phone')
                                            <p x-show="showErrors" class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="email" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.email_address_label') }} <span style="color: red">*</span></label>
                                        <input type="email" id="email" name="email" required
                                            class="form-input-premium @error('email') border-red-500 @enderror"
                                            placeholder="john@example.com"
                                            oninput=this.value=this.value.replace(/[^a-zA-Z0-9._%+-@]/g,'')>
                                        @error('email')
                                            <p x-show="showErrors" class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
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
                                        <label for="vehicle_type" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.vehicle_type') }} <span style="color: red">*</span></label>
                                        <select id="vehicle_type" name="vehicle_type" required class="form-input-premium @error('vehicle_type') border-red-500 @enderror">
                                            <option value="">{{ __('quote.select_type') }}</option>
                                            <option value="sedan">Sedan / Hatchback</option>
                                            <option value="suv">SUV / 4x4</option>
                                            <option value="truck">Truck / Bakkie</option>
                                            <option value="van">Van / Minibus</option>
                                            <option value="heavy">Heavy Machinery</option>
                                            <option value="fleet">Fleet Vehicle</option>
                                            <option value="other">Other</option>
                                        </select>
                                        @error('vehicle_type')
                                            <p x-show="showErrors" class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
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
                                            placeholder="e.g., B 123 ABC"
                                            oninput="this.value = this.value.toUpperCase()">
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

                                <div class="grid md:grid-cols-2 gap-6" x-data="{
                                        selectedGlassType: null,
                                        subCategories: [],
                                        loading: false,
                                        loadSubCategories: function(glassTypeId) {
                                            if (!glassTypeId) {
                                                this.subCategories = [];
                                                return;
                                            }

                                            this.loading = true;
                                            this.subCategories = [];

                                            loadSubCategories(glassTypeId)
                                                .then(subCategories => {
                                                    this.subCategories = subCategories;
                                                })
                                                .catch(error => {
                                                    console.error('Failed to load sub-categories:', error);
                                                })
                                                .finally(() => {
                                                    this.loading = false;
                                                });
                                        }
                                    }">
                                    <div>
                                        <label for="glass_type_id" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.glass_type') }} <span style="color: red">*</span></label>
                                        <select
                                            id="glass_type_id"
                                            name="glass_type_id"
                                            required
                                            class="form-input-premium @error('glass_type_id') border-red-500 @enderror"
                                            x-model="selectedGlassType"
                                            @change="loadSubCategories($event.target.value)"
                                        >
                                            <option value="">{{ __('quote.select_glass_type') }}</option>
                                            @foreach($glassTypes as $glassType)
                                            <option value="{{ $glassType->id }}">{{ $glassType->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('glass_type_id')
                                            <p x-show="showErrors" class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="glass_sub_category_id" class="block text-sm font-medium text-[#A1A1AA] mb-2">
                                            {{ __('quote.glass_sub_category') }}
                                            <span class="text-xs text-[#71717A] font-normal" x-show="!selectedGlassType">(select glass type first)</span>
                                            <span class="text-red-500" x-show="selectedGlassType && subCategories.length === 0 && !loading">*</span>
                                        </label>
                                        <select
                                            id="glass_sub_category_id"
                                            name="glass_sub_category_id"
                                            class="form-input-premium @error('glass_sub_category_id') border-red-500 @enderror"
                                            :disabled="!selectedGlassType || loading"
                                            x-show="selectedGlassType"
                                            :required="subCategories.length > 0"
                                        >
                                            <option value="" x-show="!loading">{{ __('quote.select_sub_category') }}</option>
                                            <template x-for="subCategory in subCategories" :key="subCategory.id">
                                                <option :value="subCategory.id" x-text="subCategory.name"></option>
                                            </template>
                                        </select>
                                        @error('glass_sub_category_id')
                                            <p x-show="showErrors" class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        <div x-show="loading" class="flex items-center gap-2 mt-2">
                                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-sm text-[#A1A1AA]">Loading sub-categories...</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-1 gap-6">
                                    <div>
                                        <label for="service_type_id" class="block text-sm font-medium text-[#A1A1AA] mb-2">{{ __('quote.service_type') }} <span style="color: red">*</span></label>
                                        <select id="service_type_id" name="service_type_id" required class="form-input-premium @error('service_type_id') border-red-500 @enderror">
                                            <option value="">{{ __('quote.select_service') }}</option>
                                            @foreach($serviceTypes as $serviceType)
                                            <option value="{{ $serviceType->id }}">{{ $serviceType->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('service_type_id')
                                            <p x-show="showErrors" class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
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

                                <x-ui.checkbox
                                    name="mobile_service"
                                    value="1"
                                    label="{{ __('quote.mobile_service') }}"
                                />
                            </div>

                            <!-- Submit -->
                            <div class="pt-10 border-t border-white/5">
                                <button type="submit"
                                    class="btn-premium glow-red-subtle w-full md:w-auto text-lg px-12 py-4"
                                    :disabled="isSubmitting || isUploading"
                                    :class="{ 'opacity-75 cursor-not-allowed': isSubmitting || isUploading }">
                                    <span x-show="!isSubmitting && !isUploading">{{ __('quote.submit_quote') }}</span>
                                    <span x-show="isUploading" class="flex items-center gap-2">
                                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Uploading image...
                                    </span>
                                    <span x-show="isSubmitting && !isUploading" class="flex items-center gap-2">
                                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Submitting...
                                    </span>
                                    <svg x-show="!isSubmitting && !isUploading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                                <p class="text-[#71717A] text-sm mt-4" x-show="isUploading">
                                    Please wait for the image to finish uploading before submitting.
                                </p>
                                <p class="text-[#71717A] text-sm mt-4" x-show="!isUploading">
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
    // Function to load sub-categories via AJAX
    function loadSubCategories(glassTypeId) {
        if (!glassTypeId) {
            return Promise.resolve([]);
        }

        return fetch(`{{ route('api.glass-types.sub-categories', ':id') }}`.replace(':id', glassTypeId))
            .then(response => response.json())
            .then(data => data.sub_categories || [])
            .catch(error => {
                console.error('Error loading sub-categories:', error);
                return [];
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof ImageUploader !== 'undefined') {
            // Get Alpine.js component reference for state synchronization
            const form = document.querySelector('form[x-data]');
            let alpineComponent = null;

            // Find the Alpine.js component
            if (form && form._x_dataStack) {
                alpineComponent = form._x_dataStack[0];
            }

            const uploader = new ImageUploader({
                fileInput: document.querySelector('input[name="image"]'),
                previewContainer: document.getElementById('quote-image-preview'),
                progressContainer: document.getElementById('quote-image-progress'),
                hiddenInput: document.getElementById('quote-image-path'),
                uploadUrl: '{{ route("admin.image-upload") }}',
                csrfToken: '{{ csrf_token() }}',
                maxSize: 2 * 1024 * 1024, // 2MB
                acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
                onUploadStart: function() {
                    // Sync with Alpine.js
                    if (alpineComponent && alpineComponent.isUploading !== undefined) {
                        alpineComponent.isUploading = true;
                    }
                    // Dispatch custom event for any other listeners
                    form?.dispatchEvent(new CustomEvent('upload:start'));
                },
                onUploadEnd: function() {
                    // Sync with Alpine.js
                    if (alpineComponent && alpineComponent.isUploading !== undefined) {
                        alpineComponent.isUploading = false;
                    }
                    form?.dispatchEvent(new CustomEvent('upload:end'));
                },
                onUploadComplete: function(response) {
                    console.log('Image uploaded successfully:', response);
                    if (alpineComponent && alpineComponent.hasImage !== undefined) {
                        alpineComponent.hasImage = true;
                    }
                },
                onUploadError: function(message) {
                    console.error('Upload error:', message);
                }
            });

            // Also expose uploader instance for debugging
            window.quoteUploader = uploader;
        }
    });
</script>
