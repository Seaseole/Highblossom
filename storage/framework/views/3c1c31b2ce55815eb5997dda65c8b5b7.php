<?php if (isset($component)) { $__componentOriginal501803f3e4defcbbeaedee798b98ded4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal501803f3e4defcbbeaedee798b98ded4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::admin','data' => ['title' => 'Company Settings']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Company Settings']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div
        class="mx-auto max-w-5xl space-y-10 py-10"
        x-data="{
        tab: '<?php echo e(request()->query('tab', 'general')); ?>',
        whatsappNumbers: <?php echo e(\Illuminate\Support\Js::from($settings['whatsapp_additional_numbers'] ?? [])); ?>,
        workingHours: <?php echo e(\Illuminate\Support\Js::from($settings['working_hours'] ?? [])); ?>,
        announcements: <?php echo e(\Illuminate\Support\Js::from($settings['announcements'] ?? [])); ?>,
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
    }"
    >
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Company Settings</h1>
            <p class="text-gray-500 dark:text-gray-400">Manage your business information and dynamic variables.</p>
        </div>

        <form
            action="<?php echo e(route('admin.settings.update')); ?>"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-8"
        >
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="hidden" name="tab" :value="tab" />

            <!-- Tabs Navigation -->
            <div class="flex space-x-1 border-b border-gray-200 dark:border-white/10">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                    'general' => 'General',
                    'hours' => 'Hours',
                    'assets' => 'Branding',
                    'localization' => 'Locale',
                    'social' => 'Social',
                    'notifications' => 'Notifications',
                    'announcements' => 'Announcements',
                    'system_config' => 'System Config',
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <button
                        type="button"
                        @click="tab = '<?php echo e($key); ?>'"
                        :class="tab === '<?php echo e($key); ?>' ? 'border-gray-900 dark:border-white text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                        class="border-b-2 px-1 pb-4 text-sm font-medium transition-colors"
                    >
                        <?php echo e($label); ?>

                    </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <!-- Tab Contents -->
            <div class="space-y-8">
                <!-- General Tab -->
                <div
                    x-show="tab === 'general'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                >
                    <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">General Information</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Business Name</label>
                            <input
                                type="text"
                                name="company_name"
                                value="<?php echo e(old('company_name', $settings['company_name'])); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Logo Text</label>
                            <input
                                type="text"
                                name="logo_text"
                                value="<?php echo e(old('logo_text', $settings['logo_text'])); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Primary Email</label>
                            <input
                                type="email"
                                name="primary_email"
                                value="<?php echo e(old('primary_email', $settings['primary_email'])); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Primary Phone</label>
                            <input
                                type="text"
                                name="primary_phone"
                                value="<?php echo e(old('primary_phone', $settings['primary_phone'])); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Business Address</label>
                            <textarea
                                name="address"
                                rows="3"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            ><?php echo e(old('address', $settings['address'])); ?></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Google Maps API Key</label>
                            <input
                                type="text"
                                name="google_maps_api_key"
                                value="<?php echo e(old('google_maps_api_key', $settings['google_maps_api_key'])); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                placeholder="AIzaSy..."
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Map Directions Link</label>
                            <input
                                type="url"
                                name="map_directions_link"
                                value="<?php echo e(old('map_directions_link', $settings['map_directions_link'])); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                placeholder="https://maps.app.goo.gl/..."
                            />
                        </div>
                    </div>
                </div>

                <!-- Business Hours Tab -->
                <div
                    x-show="tab === 'hours'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Weekly Business Hours</h3>
                    <div class="space-y-4">
                        <?php
                            $days = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5">
                                <div class="w-32">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e($label); ?></label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="working_hours[<?php echo e($key); ?>][is_closed]"
                                        value="1"
                                        <?php echo e(($settings['working_hours'][$key]['is_closed'] ?? false) ? 'checked' : ''); ?>

                                        class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                                    />
                                    <span class="text-sm text-gray-500">Closed</span>
                                </div>
                                <div class="flex flex-1 gap-4">
                                    <input
                                        type="time"
                                        name="working_hours[<?php echo e($key); ?>][open]"
                                        value="<?php echo e($settings['working_hours'][$key]['open'] ?? ''); ?>"
                                        class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none dark:border-white/10 dark:bg-white/5"
                                    />
                                    <input
                                        type="time"
                                        name="working_hours[<?php echo e($key); ?>][close]"
                                        value="<?php echo e($settings['working_hours'][$key]['close'] ?? ''); ?>"
                                        class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none dark:border-white/10 dark:bg-white/5"
                                    />
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>

                <!-- Branding Tab -->
                <div
                    x-show="tab === 'assets'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-8 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                    x-data="{
                        logoPreview: '<?php echo e($settings['business_logo'] ? Storage::url($settings['business_logo']) : null); ?>',
                        faviconPreview: '<?php echo e($settings['favicon'] ? Storage::url($settings['favicon']) : (file_exists(public_path('favicon.ico')) ? '/favicon.ico' : null)); ?>',
                        handleFileSelect(event, previewKey) {
                            const file = event.target.files[0];
                            if (file) this[previewKey] = URL.createObjectURL(file);
                        }
                     }"
                >
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Branding</h3>
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div class="space-y-4" x-data="{ removeLogo: false }">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Business Logo</label>
                            <input type="hidden" name="remove_business_logo" :value="removeLogo ? 1 : 0" />
                            <div
                                class="relative flex min-h-[160px] w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white"
                                @click="if (! removeLogo) $refs.logoInput.click();"
                            >
                                <template x-if="logoPreview && ! removeLogo">
                                    <img :src="logoPreview" class="max-h-[140px] object-contain" />
                                </template>
                                <template x-if="! logoPreview || removeLogo">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Click to upload logo</span>
                                </template>
                            </div>
                            <input
                                type="file"
                                name="business_logo"
                                x-ref="logoInput"
                                class="hidden"
                                accept="image/*"
                                @change="
                                    handleFileSelect($event, 'logoPreview');
                                    removeLogo = false;
                                "
                            />
                            <button
                                type="button"
                                @click="
                                    removeLogo = true;
                                    logoPreview = null;
                                "
                                x-show="logoPreview && ! removeLogo"
                                class="inline-flex items-center gap-2 rounded-full bg-red-500 px-6 py-2 text-xs font-medium text-white shadow-sm transition-all hover:bg-red-600 active:scale-[0.98]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                Remove Logo
                            </button>
                        </div>
                        <div class="space-y-4">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Favicon</label>
                            <div
                                class="relative flex min-h-[160px] w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white"
                                @click="$refs.faviconInput.click()"
                            >
                                <template x-if="faviconPreview">
                                    <img :src="faviconPreview" class="max-h-[140px] object-contain" />
                                </template>
                                <template x-if="! faviconPreview">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Click to upload favicon</span>
                                </template>
                            </div>
                            <input
                                type="file"
                                name="favicon"
                                x-ref="faviconInput"
                                class="hidden"
                                accept="image/*"
                                @change="handleFileSelect($event, 'faviconPreview')"
                            />
                        </div>
                    </div>
                </div>

                <!-- Locale Tab -->
                <div
                    x-show="tab === 'localization'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Locale Settings</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Timezone</label>
                            <input
                                type="text"
                                name="timezone"
                                value="<?php echo e(old('timezone', $settings['timezone'])); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Currency Symbol</label>
                            <input
                                type="text"
                                name="currency_symbol"
                                value="<?php echo e(old('currency_symbol', $settings['currency_symbol'])); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Date Format</label>
                            <select
                                name="date_format"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['d/m/Y', 'm/d/Y', 'Y-m-d', 'd.m.Y', 'j M Y', 'D, j M Y']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $format): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <option
                                        value="<?php echo e($format); ?>"
                                        <?php echo e(old('date_format', $settings['date_format']) === $format ? 'selected' : ''); ?>

                                    >
                                        <?php echo e(date($format)); ?> (<?php echo e($format); ?>)
                                    </option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Time Format</label>
                            <select
                                name="time_format"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['H:i', 'h:i A', 'H:i:s', 'h:i:s A']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $format): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <option
                                        value="<?php echo e($format); ?>"
                                        <?php echo e(old('time_format', $settings['time_format']) === $format ? 'selected' : ''); ?>

                                    >
                                        <?php echo e(date($format)); ?> (<?php echo e($format); ?>)
                                    </option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Time Display</label>
                            <select
                                name="time_format_display"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            >
                                <option
                                    value="12"
                                    <?php echo e(old('time_format_display', $settings['time_format_display']) === '12' ? 'selected' : ''); ?>

                                >
                                    12-hour (AM/PM)
                                </option>
                                <option
                                    value="24"
                                    <?php echo e(old('time_format_display', $settings['time_format_display']) === '24' ? 'selected' : ''); ?>

                                >
                                    24-hour
                                </option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Locale</label>
                            <input
                                type="text"
                                name="locale"
                                value="<?php echo e(old('locale', $settings['locale'])); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                placeholder="en_GB"
                            />
                        </div>
                    </div>
                </div>

                <!-- Social Tab -->
                <div
                    x-show="tab === 'social'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-8 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Social Links</h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Facebook</label>
                                <input
                                    type="url"
                                    name="facebook_url"
                                    value="<?php echo e(old('facebook_url', $settings['facebook_url'])); ?>"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none dark:border-white/10 dark:bg-white/5"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Instagram</label>
                                <input
                                    type="url"
                                    name="instagram_url"
                                    value="<?php echo e(old('instagram_url', $settings['instagram_url'])); ?>"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none dark:border-white/10 dark:bg-white/5"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">LinkedIn</label>
                                <input
                                    type="url"
                                    name="linkedin_url"
                                    value="<?php echo e(old('linkedin_url', $settings['linkedin_url'])); ?>"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none dark:border-white/10 dark:bg-white/5"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 border-t border-gray-100 pt-8 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">WhatsApp Settings</h3>
                            <button
                                type="button"
                                @click="addNumber()"
                                class="text-sm font-medium text-gray-900 hover:underline dark:text-white"
                            >
                                + Add Additional Number
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Default WhatsApp Number</label>
                                <input
                                    type="text"
                                    name="whatsapp_number_default"
                                    value="<?php echo e(old('whatsapp_number_default', $settings['whatsapp_number_default'])); ?>"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                    placeholder="+267 ..."
                                />
                            </div>

                            <div class="space-y-4">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Additional WhatsApp Numbers</label>
                                <div class="grid gap-4">
                                    <template x-for="(number, index) in whatsappNumbers" :key="index">
                                        <div class="flex items-start gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5">
                                            <div class="grid flex-1 grid-cols-1 gap-4 md:grid-cols-2">
                                                <div class="space-y-1">
                                                    <input
                                                        type="text"
                                                        :name="`whatsapp_additional_numbers[${index}][label]`"
                                                        x-model="number.label"
                                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                                        placeholder="e.g. Sales, Support"
                                                    />
                                                </div>
                                                <div class="space-y-1">
                                                    <input
                                                        type="text"
                                                        :name="`whatsapp_additional_numbers[${index}][number]`"
                                                        x-model="number.number"
                                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                                        placeholder="+267 ..."
                                                    />
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                @click="removeNumber(index)"
                                                class="mt-auto inline-flex items-center gap-2 rounded-full bg-red-500 px-6 py-2 text-xs font-medium text-white shadow-sm transition-all hover:bg-red-600 active:scale-[0.98]"
                                            >
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
                <div
                    x-show="tab === 'notifications'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Quote Notification Emails</label>
                        <textarea
                            name="quote_notification_emails"
                            rows="3"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        ><?php echo e((string) old('quote_notification_emails', $settings['quote_notification_emails'] ?? '')); ?></textarea>
                    </div>
                </div>

                <!-- Announcements Tab -->
                <div
                    x-show="tab === 'announcements'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-8 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Announcements</h3>
                            <p class="text-sm text-gray-500">Manage the scrolling marquee messages.</p>
                        </div>
                        <label class="group relative inline-flex cursor-pointer items-center">
                            <input
                                type="checkbox"
                                name="announcement_active"
                                value="1"
                                <?php echo e($settings['announcement_active'] ? 'checked' : ''); ?>

                                class="peer sr-only"
                            />
                            <div class="peer h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-gray-900 peer-focus:outline-none after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-white/10 dark:peer-checked:bg-white dark:after:bg-gray-400"></div>
                            <span class="ml-3 text-xs font-bold tracking-widest text-gray-500 uppercase transition-colors group-hover:text-gray-900 dark:group-hover:text-white">Active</span>
                        </label>
                    </div>

                    <div class="space-y-6 border-t border-gray-100 pt-8 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Marquee Content</h4>
                            <button
                                type="button"
                                @click="addAnnouncement()"
                                class="text-sm font-medium text-gray-900 hover:underline dark:text-white"
                            >
                                + Add Message
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(announcement, index) in announcements" :key="index">
                                <div class="flex items-start gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-6 dark:border-white/5 dark:bg-white/5">
                                    <div class="flex-1 space-y-4">
                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div class="space-y-1.5">
                                                <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Message Text</label>
                                                <input
                                                    type="text"
                                                    :name="`announcements[${index}][text]`"
                                                    x-model="announcement.text"
                                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                                    placeholder="Type message..."
                                                />
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Destination</label>
                                                <div class="space-y-2">
                                                    <select
                                                        :name="`announcements[${index}][link]`"
                                                        x-model="announcement.link"
                                                        class="admin-select w-full rounded-xl border border-gray-200 px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:focus:ring-[var(--color-admin-accent)]"
                                                    >
                                                        <option value="">No Link</option>
                                                        <optgroup label="System Routes">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                                <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                        </optgroup>
                                                        <option value="custom">Custom Link...</option>
                                                        <template x-if="announcement.link && ! [<?php echo e(implode(',', array_map(fn($r) => "'$r'", array_keys($availableRoutes)))); ?>].includes(announcement.link) && announcement.link !== 'custom'">
                                                            <optgroup label="Selected Custom Link">
                                                                <option
                                                                    :value="announcement.link"
                                                                    x-text="announcement.link"
                                                                    selected
                                                                ></option>
                                                            </optgroup>
                                                        </template>
                                                    </select>
                                                    <input
                                                        type="text"
                                                        x-show="announcement.link === 'custom' || (! [<?php echo e(implode(',', array_map(fn($r) => "'$r'", array_keys($availableRoutes)))); ?>].includes(announcement.link) && announcement.link !== '' && announcement.link !== 'custom')"
                                                        x-model="announcement.link"
                                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                                        placeholder="Enter URL (e.g. https://...)"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeAnnouncement(index)"
                                        class="mt-auto inline-flex items-center gap-2 rounded-full bg-red-500 px-6 py-2 text-xs font-medium text-white shadow-sm transition-all hover:bg-red-600 active:scale-[0.98]"
                                    >
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

                <!-- System Config Tab -->
                <div
                    x-show="tab === 'system_config'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <div class="mb-6 space-y-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">System Config</h3>
                        <p class="text-sm text-gray-500">
                            Read-only environment variables. Click an input to edit its value.
                        </p>
                    </div>

                    <?php
                        $registrationEnabled = ($envConfig['FEATURES_REGISTRATION_ENABLED'] ?? 'true') === 'true';
                    ?>

                    <div class="mb-6 flex items-center justify-between rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5">
                        <div class="space-y-1">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Enable Registration</h4>
                            <p class="text-xs text-gray-500">Allow new users to register on the site.</p>
                        </div>
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="hidden" name="env[FEATURES_REGISTRATION_ENABLED]" value="false" />
                            <input
                                type="checkbox"
                                name="env[FEATURES_REGISTRATION_ENABLED]"
                                value="true"
                                <?php echo e($registrationEnabled ? 'checked' : ''); ?>

                                class="peer sr-only"
                            />
                            <div class="peer h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-gray-900 peer-focus:outline-none after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-white/10 dark:peer-checked:bg-white dark:after:bg-gray-400"></div>
                        </label>
                    </div>

                    <div class="space-y-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $envConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($key === 'FEATURES_REGISTRATION_ENABLED'): ?> <?php continue; ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="space-y-2" x-data="{ editing: false }">
                                <label class="text-[10px] font-bold tracking-widest text-gray-500 uppercase"><?php echo e($key); ?></label>
                                <input
                                    type="text"
                                    name="env[<?php echo e($key); ?>]"
                                    value="<?php echo e(old('env.'.$key, $value)); ?>"
                                    :readonly="! editing"
                                    @click="editing = true"
                                    @click.away="editing = false"
                                    :class="editing
                                        ? 'bg-white dark:bg-white/5 border-gray-900 dark:border-white focus:ring-1 focus:ring-gray-900 dark:focus:ring-white'
                                        : 'bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 cursor-pointer text-gray-500'"
                                    class="w-full rounded-xl border px-4 py-2.5 text-sm transition-all outline-none"
                                />
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6 dark:border-white/10">
                    <button
                        type="submit"
                        class="rounded-full bg-gray-900 px-6 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Save All Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal501803f3e4defcbbeaedee798b98ded4)): ?>
<?php $attributes = $__attributesOriginal501803f3e4defcbbeaedee798b98ded4; ?>
<?php unset($__attributesOriginal501803f3e4defcbbeaedee798b98ded4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal501803f3e4defcbbeaedee798b98ded4)): ?>
<?php $component = $__componentOriginal501803f3e4defcbbeaedee798b98ded4; ?>
<?php unset($__componentOriginal501803f3e4defcbbeaedee798b98ded4); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\admin\settings\index.blade.php ENDPATH**/ ?>