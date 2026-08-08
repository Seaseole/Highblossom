<?php if (isset($component)) { $__componentOriginal52b6740a4059545a9135423805a466b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52b6740a4059545a9135423805a466b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::site','data' => ['title' => 'Request a Quote']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::site'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Request a Quote']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <!-- Hero Section -->
    <section class="relative bg-[#0A0A0F] pt-32 pb-20">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <div class="mb-4 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    <?php echo e(__('quote.label')); ?>

                </div>
                <h1 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                    <?php echo e(__('quote.title')); ?>

                </h1>
                <p class="text-lg leading-relaxed text-[#A1A1AA]"><?php echo e(__('quote.description')); ?></p>
            </div>
        </div>
    </section>

    <!-- Quote Form Section -->
    <section class="bg-[#0A0A0F] py-24">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-3">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class="glass-card rounded-2xl p-8 md:p-12">
                        <form
                            action="<?php echo e(route('quote.submit')); ?>"
                            method="POST"
                            enctype="multipart/form-data"
                            class="space-y-10"
                            x-data="{
                                isSubmitting: false,
                                isUploading: false,
                                hasImage: false,
                                showErrors: true,
                                init() {
                                    setTimeout(() => (this.showErrors = false), 5000);
                                },
                            }"
                            @submit.prevent="
                                if (! isSubmitting && ! isUploading) {
                                    isSubmitting = true;
                                    $el.submit();
                                }
                            "
                        >
                            <?php echo csrf_field(); ?>
                            <input
                                type="hidden"
                                name="_idempotency_token"
                                value="<?php echo e(session()->get('quote_token', md5(uniqid()))); ?>"
                            />
                            <?php (session()->put('quote_token', md5(uniqid()))); ?>

                            <!-- Personal Information -->
                            <div>
                                <div class="mb-6 flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#DC2626]/10">
                                        <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-headline text-xl font-bold text-[#FAFAFA]">
                                            <?php echo e(__('quote.your_information')); ?>

                                        </h3>
                                        <p class="text-sm text-[#71717A]"><?php echo e(__('quote.your_info_description')); ?></p>
                                    </div>
                                </div>
                                <div class="grid gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="name" class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                            ><?php echo e(__('quote.full_name_label')); ?> <span style="color: red">*</span></label>
                                        <input
                                            type="text"
                                            id="name"
                                            name="name"
                                            required
                                            class="form-input-premium <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            placeholder="John Doe"
                                            oninput="this.value=this.value.replace(/[^a-zA-Z"
                                            ]/g,'')"
                                        />
                                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p x-show="showErrors" class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <label for="phone" class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                            ><?php echo e(__('quote.phone_number_label')); ?>

                                            <span style="color: red">*</span></label>
                                        <input
                                            type="tel"
                                            id="phone"
                                            name="phone"
                                            required
                                            class="form-input-premium <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            placeholder="267 XX XXX XXX"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        />
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p x-show="showErrors" class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="email" class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                            ><?php echo e(__('quote.email_address_label')); ?>

                                            <span style="color: red">*</span></label>
                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            required
                                            class="form-input-premium <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            placeholder="john@example.com"
                                            oninput="this.value = this.value.replace(/[^a-zA-Z0-9._%+-@]/g, '')"
                                        />
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p x-show="showErrors" class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Vehicle Information -->
                            <div class="border-t border-white/5 pt-10">
                                <div class="mb-6 flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#DC2626]/10">
                                        <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-headline text-xl font-bold text-[#FAFAFA]">
                                            <?php echo e(__('quote.vehicle_details')); ?>

                                        </h3>
                                        <p class="text-sm text-[#71717A]"><?php echo e(__('quote.vehicle_info_description')); ?></p>
                                    </div>
                                </div>
                                <div class="grid gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="vehicle_type" class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                            ><?php echo e(__('quote.vehicle_type')); ?> <span style="color: red">*</span></label>
                                        <select
                                            id="vehicle_type"
                                            name="vehicle_type"
                                            required
                                            class="form-input-premium <?php $__errorArgs = ['vehicle_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        >
                                            <option value=""><?php echo e(__('quote.select_type')); ?></option>
                                            <option value="sedan">Sedan / Hatchback</option>
                                            <option value="suv">SUV / 4x4</option>
                                            <option value="truck">Truck / Bakkie</option>
                                            <option value="van">Van / Minibus</option>
                                            <option value="heavy">Heavy Machinery</option>
                                            <option value="fleet">Fleet Vehicle</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['vehicle_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p x-show="showErrors" class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <label
                                            for="make_model"
                                            class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                        ><?php echo e(__('quote.make_model')); ?></label>
                                        <input
                                            type="text"
                                            id="make_model"
                                            name="make_model"
                                            class="form-input-premium"
                                            placeholder="e.g., Toyota Hilux 2020"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="reg_number"
                                            class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                        ><?php echo e(__('quote.registration_number')); ?></label>
                                        <input
                                            type="text"
                                            id="reg_number"
                                            name="reg_number"
                                            class="form-input-premium"
                                            placeholder="e.g., B 123 ABC"
                                            oninput="this.value = this.value.toUpperCase()"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="year"
                                            class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                        ><?php echo e(__('quote.vehicle_year')); ?></label>
                                        <input
                                            type="number"
                                            id="year"
                                            name="year"
                                            min="1980"
                                            max="2026"
                                            class="form-input-premium"
                                            placeholder="2020"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Service Required -->
                            <div class="border-t border-white/5 pt-10">
                                <div class="mb-6 flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#DC2626]/10">
                                        <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-headline text-xl font-bold text-[#FAFAFA]">
                                            <?php echo e(__('quote.service_required')); ?>

                                        </h3>
                                        <p class="text-sm text-[#71717A]"><?php echo e(__('quote.service_description')); ?></p>
                                    </div>
                                </div>

                                <div
                                    class="grid gap-6 md:grid-cols-2"
                                    x-data="{
                                        selectedGlassType: null,
                                        subCategories: [],
                                        loading: false,
                                        loadSubCategories: function (glassTypeId) {
                                            if (! glassTypeId) {
                                                this.subCategories = [];
                                                return;
                                            }

                                            this.loading = true;
                                            this.subCategories = [];

                                            loadSubCategories(glassTypeId)
                                                .then((subCategories) => {
                                                    this.subCategories = subCategories;
                                                })
                                                .catch((error) => {
                                                    console.error('Failed to load sub-categories:', error);
                                                })
                                                .finally(() => {
                                                    this.loading = false;
                                                });
                                        },
                                    }"
                                >
                                    <div>
                                        <label for="glass_type_id" class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                            ><?php echo e(__('quote.glass_type')); ?> <span style="color: red">*</span></label>
                                        <select
                                            id="glass_type_id"
                                            name="glass_type_id"
                                            required
                                            class="form-input-premium <?php $__errorArgs = ['glass_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            x-model="selectedGlassType"
                                            @change="loadSubCategories($event.target.value)"
                                        >
                                            <option value=""><?php echo e(__('quote.select_glass_type')); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $glassTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $glassType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($glassType->id); ?>"><?php echo e($glassType->name); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['glass_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p x-show="showErrors" class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <label
                                            for="glass_sub_category_id"
                                            class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                        >
                                            <?php echo e(__('quote.glass_sub_category')); ?>

                                            <span class="text-xs font-normal text-[#71717A]" x-show="! selectedGlassType"
                                                >(select glass type first)</span>
                                            <span
                                                class="text-red-500"
                                                x-show="selectedGlassType && subCategories.length === 0 && ! loading"
                                            >*</span>
                                        </label>
                                        <select
                                            id="glass_sub_category_id"
                                            name="glass_sub_category_id"
                                            class="form-input-premium <?php $__errorArgs = ['glass_sub_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            :disabled="! selectedGlassType || loading"
                                            x-show="selectedGlassType"
                                            :required="subCategories.length > 0"
                                        >
                                            <option value="" x-show="! loading">
                                                <?php echo e(__('quote.select_sub_category')); ?>

                                            </option>
                                            <template x-for="subCategory in subCategories" :key="subCategory.id">
                                                <option :value="subCategory.id" x-text="subCategory.name"></option>
                                            </template>
                                        </select>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['glass_sub_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p x-show="showErrors" class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div x-show="loading" class="mt-2 flex items-center gap-2">
                                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-sm text-[#A1A1AA]">Loading sub-categories...</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-6 md:grid-cols-1">
                                    <div>
                                        <label
                                            for="service_type_id"
                                            class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                        ><?php echo e(__('quote.service_type')); ?> <span style="color: red">*</span></label>
                                        <select
                                            id="service_type_id"
                                            name="service_type_id"
                                            required
                                            class="form-input-premium <?php $__errorArgs = ['service_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        >
                                            <option value=""><?php echo e(__('quote.select_service')); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $serviceTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $serviceType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($serviceType->id); ?>"><?php echo e($serviceType->name); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['service_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p x-show="showErrors" class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="border-t border-white/5 pt-10">
                                <div class="mb-6 flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#DC2626]/10">
                                        <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-headline text-xl font-bold text-[#FAFAFA]">
                                            <?php echo e(__('quote.additional_info')); ?>

                                        </h3>
                                        <p class="text-sm text-[#71717A]">
                                            <?php echo e(__('quote.additional_info_description')); ?>

                                        </p>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <h3 class="mb-2 text-lg font-medium text-[#FAFAFA]">
                                        <?php echo e(__('quote.visual_assessment')); ?>

                                    </h3>
                                    <p class="mb-4 text-sm text-[#71717A]"><?php echo e(__('quote.upload_description')); ?></p>

                                    <input type="hidden" name="image_path" id="quote-image-path" />

                                    <div id="quote-image-preview" class="mb-4"></div>
                                    <div id="quote-image-progress"></div>

                                    <div class="mt-4 flex justify-center rounded-lg border border-dashed border-white/20 bg-white/[0.02] px-6 py-10 transition-colors hover:bg-white/[0.04]">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-[#DC2626]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.69a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                            </svg>
                                            <div class="mt-4 flex text-sm leading-6 text-[#A1A1AA]">
                                                <label
                                                    for="file-upload"
                                                    class="relative cursor-pointer rounded-md font-semibold text-[#DC2626] focus-within:ring-2 focus-within:ring-[#DC2626] focus-within:ring-offset-2 focus-within:outline-none hover:text-[#EF4444]"
                                                >
                                                    <span><?php echo e(__('quote.click_to_upload')); ?></span>
                                                    <input
                                                        id="file-upload"
                                                        name="image"
                                                        type="file"
                                                        accept="image/*"
                                                        class="sr-only"
                                                    />
                                                </label>
                                            </div>
                                            <p class="text-xs leading-5 text-[#71717A]">
                                                <?php echo e(__('quote.file_requirements')); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($component)) { $__componentOriginala40cc9faf0a70b4042aba6747c772818 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala40cc9faf0a70b4042aba6747c772818 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.checkbox','data' => ['name' => 'mobile_service','value' => '1','label' => ''.e(__('quote.mobile_service')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'mobile_service','value' => '1','label' => ''.e(__('quote.mobile_service')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala40cc9faf0a70b4042aba6747c772818)): ?>
<?php $attributes = $__attributesOriginala40cc9faf0a70b4042aba6747c772818; ?>
<?php unset($__attributesOriginala40cc9faf0a70b4042aba6747c772818); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala40cc9faf0a70b4042aba6747c772818)): ?>
<?php $component = $__componentOriginala40cc9faf0a70b4042aba6747c772818; ?>
<?php unset($__componentOriginala40cc9faf0a70b4042aba6747c772818); ?>
<?php endif; ?>
                            </div>

                            <!-- Submit -->
                            <div class="border-t border-white/5 pt-10">
                                <button
                                    type="submit"
                                    class="btn-premium glow-red-subtle w-full px-12 py-4 text-lg md:w-auto"
                                    :disabled="isSubmitting || isUploading"
                                    :class="{ 'opacity-75 cursor-not-allowed': isSubmitting || isUploading }"
                                >
                                    <span
                                        x-show="! isSubmitting && ! isUploading"
                                        x-cloak
                                    ><?php echo e(__('quote.submit_quote')); ?></span>
                                    <span x-show="isUploading" x-cloak class="flex items-center gap-2">
                                        <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Uploading image...
                                    </span>
                                    <span x-show="isSubmitting && ! isUploading" x-cloak class="flex items-center gap-2">
                                        <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Submitting...
                                    </span>
                                    <svg x-show="
                                            ! isSubmitting && ! isUploading
                                        " x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                                <p class="mt-4 text-sm text-[#71717A]" x-show="isUploading">
                                    Please wait for the image to finish uploading before submitting.
                                </p>
                                <p class="mt-4 text-sm text-[#71717A]" x-show="! isUploading">
                                    <?php echo e(__('quote.submit_disclaimer')); ?>

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
                            <h3 class="font-headline mb-4 text-lg font-bold text-[#FAFAFA]">
                                <?php echo e(__('quote.why_quote_title')); ?>

                            </h3>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3">
                                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-[#A1A1AA]"><?php echo e(__('quote.benefit_1')); ?></span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-[#A1A1AA]"><?php echo e(__('quote.benefit_2')); ?></span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-[#A1A1AA]"><?php echo e(__('quote.benefit_3')); ?></span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-[#A1A1AA]"><?php echo e(__('quote.benefit_4')); ?></span>
                                </li>
                            </ul>
                        </div>

                        <!-- Contact Card -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($primaryPhone): ?>
                            <div class="glass-card rounded-2xl p-6">
                                <h3 class="font-headline mb-4 text-lg font-bold text-[#FAFAFA]">
                                    <?php echo e(__('quote.prefer_to_call')); ?>

                                </h3>
                                <p class="mb-4 text-sm text-[#A1A1AA]"><?php echo e(__('quote.call_description')); ?></p>
                                <a
                                    href="tel:<?php echo e(str_replace([' ', '-', '(', ')'], '', $primaryPhone)); ?>"
                                    class="flex items-center gap-3 font-semibold text-[#DC2626] hover:underline"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <?php echo e($primaryPhone); ?>

                                </a>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Emergency Banner -->
                        <div class="rounded-2xl border border-[#DC2626]/30 bg-[#DC2626]/10 p-6">
                            <div class="mb-3 flex items-center gap-2">
                                <span class="h-2 w-2 animate-pulse rounded-full bg-[#DC2626]"></span>
                                <span class="text-sm font-semibold text-[#DC2626] uppercase"><?php echo e(__('quote.emergency_24_7')); ?></span>
                            </div>
                            <p class="mb-4 text-sm text-[#A1A1AA]"><?php echo e(__('quote.emergency_description')); ?></p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($primaryPhone): ?>
                                <a
                                    href="tel:<?php echo e(str_replace([' ', '-', '(', ')'], '', $primaryPhone)); ?>"
                                    class="btn-premium w-full py-3 text-sm"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span><?php echo e(__('quote.call_emergency')); ?></span>
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal52b6740a4059545a9135423805a466b9)): ?>
<?php $attributes = $__attributesOriginal52b6740a4059545a9135423805a466b9; ?>
<?php unset($__attributesOriginal52b6740a4059545a9135423805a466b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal52b6740a4059545a9135423805a466b9)): ?>
<?php $component = $__componentOriginal52b6740a4059545a9135423805a466b9; ?>
<?php unset($__componentOriginal52b6740a4059545a9135423805a466b9); ?>
<?php endif; ?>

<script src="<?php echo e(asset('js/image-upload.js')); ?>"></script>
<script>
    // Function to load sub-categories via AJAX
    function loadSubCategories(glassTypeId) {
        if (!glassTypeId) {
            return Promise.resolve([]);
        }

        return fetch(`<?php echo e(route('api.glass-types.sub-categories', ':id')); ?>`.replace(':id', glassTypeId))
            .then((response) => response.json())
            .then((data) => data.sub_categories || [])
            .catch((error) => {
                console.error('Error loading sub-categories:', error);
                return [];
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
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
                uploadUrl: '<?php echo e(route("admin.image-upload")); ?>',
                csrfToken: '<?php echo e(csrf_token()); ?>',
                folder: 'quotes',
                maxSize: 2 * 1024 * 1024, // 2MB
                acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
                onUploadStart: function () {
                    // Sync with Alpine.js
                    if (alpineComponent && alpineComponent.isUploading !== undefined) {
                        alpineComponent.isUploading = true;
                    }
                    // Dispatch custom event for any other listeners
                    form?.dispatchEvent(new CustomEvent('upload:start'));
                },
                onUploadEnd: function () {
                    // Sync with Alpine.js
                    if (alpineComponent && alpineComponent.isUploading !== undefined) {
                        alpineComponent.isUploading = false;
                    }
                    form?.dispatchEvent(new CustomEvent('upload:end'));
                },
                onUploadComplete: function (response) {
                    console.log('Image uploaded successfully:', response);
                    if (alpineComponent && alpineComponent.hasImage !== undefined) {
                        alpineComponent.hasImage = true;
                    }
                },
                onUploadError: function (message) {
                    console.error('Upload error:', message);
                },
            });

            // Also expose uploader instance for debugging
            window.quoteUploader = uploader;
        }
    });
</script>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\site\quote.blade.php ENDPATH**/ ?>