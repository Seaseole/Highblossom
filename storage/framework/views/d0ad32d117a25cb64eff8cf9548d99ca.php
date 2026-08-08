<?php if (isset($component)) { $__componentOriginal52b6740a4059545a9135423805a466b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52b6740a4059545a9135423805a466b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::site','data' => ['title' => 'Contact Us']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::site'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Contact Us']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <!-- Hero Section -->
    <section class="relative bg-[#0A0A0F] pt-32 pb-20">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <div class="mb-4 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    <?php echo e(__('contact.label')); ?>

                </div>
                <h1 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                    <?php echo e(__('contact.title')); ?>

                </h1>
                <p class="text-lg leading-relaxed text-[#A1A1AA]"><?php echo e(__('contact.description')); ?></p>
            </div>
        </div>
    </section>

    <!-- Contact Methods Grid -->
    <section class="bg-[#0A0A0F] py-24">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $contactNumbers->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $number): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="glass-card group rounded-2xl p-8 text-center transition-all hover:bg-white/[0.06]">
                        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($number->is_whatsapp): ?>
                                <svg class="h-8 w-8 text-[#DC2626]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.447-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 5.834h-.004a9.311 9.311 0 01-4.51-1.177l-.323-.191-3.35.879.893-3.267-.209-.332a9.309 9.309 0 01-1.38-4.984c0-5.149 4.19-9.338 9.346-9.338a9.307 9.307 0 016.607 2.737 9.32 9.32 0 012.73 6.609c-.002 5.15-4.191 9.338-9.346 9.338m7.642-16.862A11.292 11.292 0 0012.237 0C5.636 0 .17 5.467.17 12.067c0 2.126.556 4.197 1.607 6.017L0 24l6.256-1.64a11.248 11.248 0 005.98 1.608c6.598 0 11.965-5.468 11.965-12.067a11.956 11.956 0 00-3.508-8.47" />
                                </svg>
                            <?php else: ?>
                                <svg class="h-8 w-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                            <?php echo e($number->is_whatsapp ? __('contact.whatsapp') : $number->label); ?>

                        </div>
                        <div class="font-headline mb-2 text-2xl font-bold text-[#FAFAFA]">
                            <?php echo e($number->formatted_number); ?>

                        </div>
                        <p class="mb-6 text-sm text-[#71717A]">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($number->is_primary): ?>
                                <?php
                                    try {
                                        $dayOrder = ['monday' => 'Mon', 'tuesday' => 'Tue', 'wednesday' => 'Wed', 'thursday' => 'Thu', 'friday' => 'Fri', 'saturday' => 'Sat', 'sunday' => 'Sun'];
                                        $openDays = [];
                                        $closedDays = [];

                                        if (isset($workingHours) && is_array($workingHours)) {
                                            foreach ($dayOrder as $key => $abbr) {
                                                if (isset($workingHours[$key]) && ! ($workingHours[$key]['is_closed'] ?? false)) {
                                                    $format = ($timeFormatDisplay ?? '12') === '24' ? 'H:i' : 'g:i A';
                                                    $time = date($format, strtotime($workingHours[$key]['open'] ?? '07:30')).' – '.date($format, strtotime($workingHours[$key]['close'] ?? '17:00'));
                                                    $openDays[$key] = ['abbr' => $abbr, 'time' => $time];
                                                } else {
                                                    $closedDays[] = $abbr;
                                                }
                                            }

                                            // Group consecutive days with same hours
                                            $groupedDays = [];
                                            $currentGroup = [];
                                            $currentTime = null;
                                            $prevKey = null;
                                            $dayKeys = array_keys($dayOrder);

                                            foreach ($dayKeys as $key) {
                                                if (! isset($openDays[$key])) {
                                                    continue;
                                                }

                                                $dayData = $openDays[$key];
                                                $time = $dayData['time'];

                                                // Check if consecutive and same time
                                                $isConsecutive = $prevKey !== null && array_search($key, $dayKeys) === array_search($prevKey, $dayKeys) + 1;

                                                if ($time === $currentTime && $isConsecutive) {
                                                    $currentGroup[] = $dayData['abbr'];
                                                } else {
                                                    if (! empty($currentGroup)) {
                                                        $groupedDays[] = ['days' => $currentGroup, 'time' => $currentTime];
                                                    }
                                                    $currentGroup = [$dayData['abbr']];
                                                    $currentTime = $time;
                                                }
                                                $prevKey = $key;
                                            }

                                            if (! empty($currentGroup)) {
                                                $groupedDays[] = ['days' => $currentGroup, 'time' => $currentTime];
                                            }

                                            // Modern professional format
                                            $formatted = [];
                                            foreach ($groupedDays as $group) {
                                                $dayLabel = count($group['days']) > 2
                                                    ? $group['days'][0].'–'.end($group['days'])
                                                    : implode(' & ', $group['days']);
                                                $formatted[] = $dayLabel.' · '.$group['time'];
                                            }

                                            if (! empty($closedDays)) {
                                                $closedLabel = count($closedDays) > 2
                                                    ? $closedDays[0].'–'.end($closedDays)
                                                    : implode(' & ', $closedDays);
                                                $formatted[] = $closedLabel.' · Closed';
                                            }

                                            echo implode(' | ', $formatted);
                                        } else {
                                            echo 'Mon–Fri · 7:30 AM – 5:00 PM | Sat · 8:00 AM – 1:00 PM | Sun · Closed';
                                        }
                                    } catch (\Exception $e) {
                                        echo 'Mon–Fri · 7:30 AM – 5:00 PM | Sat · 8:00 AM – 1:00 PM | Sun · Closed';
                                    }
                                ?>
                            <?php elseif($number->is_whatsapp): ?>
                                <?php echo e(__('contact.available_24_7')); ?>

                            <?php else: ?>
                                <?php echo e($number->label); ?> <?php echo e(__('contact.line_label')); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                        <a
                            href="<?php echo e($number->is_whatsapp ? 'https://wa.me/' . str_replace(['+', ' '], '', $number->phone_number) : 'tel:' . $number->phone_number); ?>"
                            target="<?php echo e($number->is_whatsapp ? '_blank' : '_self'); ?>"
                            class="btn-premium"
                        >
                            <span><?php echo e($number->is_whatsapp ? __('contact.chat_on_whatsapp') : __('contact.call_now')); ?></span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <!-- Fallback Cards -->
                    <div class="glass-card group rounded-2xl p-8 text-center transition-all hover:bg-white/[0.06]">
                        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                            <svg class="h-8 w-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                            <?php echo e(__('contact.phone')); ?>

                        </div>
                        <div class="font-headline mb-2 text-2xl font-bold text-[#FAFAFA]">
                            <?php echo e($primaryPhone ?? '+267 123 456 78'); ?>

                        </div>
                        <p class="mb-6 text-sm text-[#71717A]">
                            <?php
                                try {
                                    $dayOrder = ['monday' => 'Mon', 'tuesday' => 'Tue', 'wednesday' => 'Wed', 'thursday' => 'Thu', 'friday' => 'Fri', 'saturday' => 'Sat', 'sunday' => 'Sun'];
                                    $openDays = [];
                                    $closedDays = [];

                                    if (isset($workingHours) && is_array($workingHours)) {
                                        foreach ($dayOrder as $key => $abbr) {
                                            if (isset($workingHours[$key]) && ! ($workingHours[$key]['is_closed'] ?? false)) {
                                                $format = ($timeFormatDisplay ?? '12') === '24' ? 'H:i' : 'g:i A';
                                                $time = date($format, strtotime($workingHours[$key]['open'] ?? '07:30')).' – '.date($format, strtotime($workingHours[$key]['close'] ?? '17:00'));
                                                $openDays[$key] = ['abbr' => $abbr, 'time' => $time];
                                            } else {
                                                $closedDays[] = $abbr;
                                            }
                                        }

                                        // Group consecutive days with same hours
                                        $groupedDays = [];
                                        $currentGroup = [];
                                        $currentTime = null;
                                        $prevKey = null;
                                        $dayKeys = array_keys($dayOrder);

                                        foreach ($dayKeys as $key) {
                                            if (! isset($openDays[$key])) {
                                                continue;
                                            }

                                            $dayData = $openDays[$key];
                                            $time = $dayData['time'];

                                            // Check if consecutive and same time
                                            $isConsecutive = $prevKey !== null && array_search($key, $dayKeys) === array_search($prevKey, $dayKeys) + 1;

                                            if ($time === $currentTime && $isConsecutive) {
                                                $currentGroup[] = $dayData['abbr'];
                                            } else {
                                                if (! empty($currentGroup)) {
                                                    $groupedDays[] = ['days' => $currentGroup, 'time' => $currentTime];
                                                }
                                                $currentGroup = [$dayData['abbr']];
                                                $currentTime = $time;
                                            }
                                            $prevKey = $key;
                                        }

                                        if (! empty($currentGroup)) {
                                            $groupedDays[] = ['days' => $currentGroup, 'time' => $currentTime];
                                        }

                                        // Modern professional format
                                        $formatted = [];
                                        foreach ($groupedDays as $group) {
                                            $dayLabel = count($group['days']) > 2
                                                ? $group['days'][0].'–'.end($group['days'])
                                                : implode(' & ', $group['days']);
                                            $formatted[] = $dayLabel.' · '.$group['time'];
                                        }

                                        if (! empty($closedDays)) {
                                            $closedLabel = count($closedDays) > 2
                                                ? $closedDays[0].'–'.end($closedDays)
                                                : implode(' & ', $closedDays);
                                            $formatted[] = $closedLabel.' · Closed';
                                        }

                                        echo implode(' | ', $formatted);
                                    } else {
                                        echo 'Mon–Fri · 7:30 AM – 5:00 PM | Sat · 8:00 AM – 1:00 PM | Sun · Closed';
                                    }
                                } catch (\Exception $e) {
                                    echo 'Mon–Fri · 7:30 AM – 5:00 PM | Sat · 8:00 AM – 1:00 PM | Sun · Closed';
                                }
                            ?>
                        </p>
                        <a
                            href="tel:<?php echo e(str_replace([' ', '-', '(', ')'], '', $primaryPhone ?? '+26712345678')); ?>"
                            class="btn-premium"
                        >
                            <span><?php echo e(__('contact.call_now')); ?></span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>

                    <div class="glass-card group rounded-2xl p-8 text-center transition-all hover:bg-white/[0.06]">
                        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                            <svg class="h-8 w-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                            <?php echo e(__('contact.email')); ?>

                        </div>
                        <div class="font-headline mb-2 text-2xl font-bold text-[#FAFAFA]">
                            <?php echo e($primaryEmail ?? 'info@highblossom.co.bw'); ?>

                        </div>
                        <p class="mb-6 text-sm text-[#71717A]"><?php echo e(__('contact.reply_within_24h')); ?></p>
                        <a href="mailto:<?php echo e($primaryEmail); ?>" class="btn-premium">
                            <span><?php echo e(__('contact.send_email')); ?></span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>

                    <div class="glass-card group rounded-2xl p-8 text-center transition-all hover:bg-white/[0.06]">
                        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                            <svg class="h-8 w-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                            <?php echo e(__('contact.location')); ?>

                        </div>
                        <div class="font-headline mb-2 text-2xl font-bold text-[#FAFAFA]">
                            <?php echo e(__('contact.location_address')); ?>

                        </div>
                        <p class="mb-6 text-sm text-[#71717A]"><?php echo e(__('contact.location_details')); ?></p>
                        <a href="https://maps.google.com" target="_blank" class="btn-premium">
                            <span><?php echo e(__('contact.get_directions')); ?></span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Map & Contact Form Section -->
    <section class="border-t border-white/5 bg-gradient-to-b from-[#0A0A0F] to-[#121218] py-24">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2">
                <!-- Map -->
                <div class="relative">
                    <div class="relative h-[600px] overflow-hidden rounded-2xl">
                        <iframe
                            src="https://www.google.com/maps/embed/v1/place?key=<?php echo e($googleMapsApiKey); ?>&q=<?php echo e(urlencode($companyName)); ?>"
                            width="100%"
                            height="100%"
                            style="border: 0"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="contrast-125 grayscale"
                        >
                        </iframe>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-transparent to-transparent"></div>
                    </div>
                    <div class="glass-card absolute right-6 bottom-6 left-6 rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-[#DC2626]/10">
                                <svg class="h-6 w-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-headline mb-1 text-lg font-bold text-[#FAFAFA]"><?php echo e($companyName); ?></h3>
                                <p class="mb-3 text-sm text-[#A1A1AA]"><?php echo e($companyAddress); ?></p>
                                <a
                                    href="<?php echo e($mapDirectionsLink); ?>"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-[#DC2626] transition-colors hover:text-[#FAFAFA]"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7" />
                                    </svg>
                                    <?php echo e(__('contact.get_directions')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div>
                    <div class="glass-card rounded-2xl p-8 md:p-10">
                        <div class="mb-8 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#DC2626]/10">
                                <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-headline text-2xl font-bold text-[#FAFAFA]">
                                    <?php echo e(__('contact.send_message')); ?>

                                </h2>
                                <p class="text-sm text-[#71717A]"><?php echo e(__('contact.form_description')); ?></p>
                            </div>
                        </div>

                        <form
                            action="<?php echo e(route('contact.submit')); ?>"
                            method="POST"
                            class="space-y-6"
                            x-data="{ isSubmitting: false }"
                            @submit.prevent="
                                if (! isSubmitting) {
                                    isSubmitting = true;
                                    $el.submit();
                                }
                            "
                        >
                            <?php echo csrf_field(); ?>
                            <input
                                type="hidden"
                                name="_idempotency_token"
                                value="<?php echo e(session()->get('contact_token', md5(uniqid()))); ?>"
                            />
                            <?php (session()->put('contact_token', md5(uniqid()))); ?>
                            <div class="grid gap-6 md:grid-cols-2">
                                <div>
                                    <label for="contact_name" class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                        ><?php echo e(__('contact.full_name')); ?> *</label>
                                    <input
                                        type="text"
                                        id="contact_name"
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
                                        value="<?php echo e(old('name')); ?>"
                                        placeholder="John Doe"
                                    />
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-xs text-[#DC2626]"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label for="contact_email" class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                        ><?php echo e(__('contact.email_address')); ?> *</label>
                                    <input
                                        type="email"
                                        id="contact_email"
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
                                        value="<?php echo e(old('email')); ?>"
                                        placeholder="<?php echo e(__('contact.email_placeholder')); ?>"
                                    />
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-xs text-[#DC2626]"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <div>
                                <label
                                    for="contact_phone"
                                    class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                ><?php echo e(__('contact.phone_number')); ?></label>
                                <input
                                    type="tel"
                                    id="contact_phone"
                                    name="phone"
                                    class="form-input-premium <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('phone')); ?>"
                                    placeholder="<?php echo e(__('contact.phone_placeholder')); ?>"
                                />
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-xs text-[#DC2626]"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label for="contact_subject" class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                    ><?php echo e(__('contact.subject')); ?> *</label>
                                <select
                                    id="contact_subject"
                                    name="subject"
                                    required
                                    class="form-input-premium <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                >
                                    <option value=""><?php echo e(__('contact.select_subject')); ?></option>
                                    <option value="general" <?php echo e(old('subject') == 'general' ? 'selected' : ''); ?>>
                                        <?php echo e(__('contact.subject_general')); ?>

                                    </option>
                                    <option value="quote" <?php echo e(old('subject') == 'quote' ? 'selected' : ''); ?>>
                                        <?php echo e(__('contact.subject_quote')); ?>

                                    </option>
                                    <option value="booking" <?php echo e(old('subject') == 'booking' ? 'selected' : ''); ?>>
                                        <?php echo e(__('contact.subject_booking')); ?>

                                    </option>
                                    <option value="complaint" <?php echo e(old('subject') == 'complaint' ? 'selected' : ''); ?>>
                                        <?php echo e(__('contact.subject_complaint')); ?>

                                    </option>
                                    <option value="other" <?php echo e(old('subject') == 'other' ? 'selected' : ''); ?>>
                                        <?php echo e(__('contact.subject_other')); ?>

                                    </option>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-xs text-[#DC2626]"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label for="contact_message" class="mb-2 block text-sm font-medium text-[#A1A1AA]"
                                    ><?php echo e(__('contact.message')); ?> *</label>
                                <textarea
                                    id="contact_message"
                                    name="message"
                                    rows="4"
                                    required
                                    class="form-input-premium resize-none <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="<?php echo e(__('contact.message_placeholder')); ?>"
                                ><?php echo e(old('message')); ?></textarea>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-xs text-[#DC2626]"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <button
                                type="submit"
                                class="btn-premium glow-red-subtle w-full py-4 text-lg"
                                :disabled="isSubmitting"
                                :class="{ 'opacity-75 cursor-not-allowed': isSubmitting }"
                            >
                                <span x-show="! isSubmitting" x-cloak><?php echo e(__('contact.submit_button')); ?></span>
                                <span x-show="isSubmitting" x-cloak class="flex items-center gap-2">
                                    <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sending...
                                </span>
                                <svg x-show="
                                        ! isSubmitting
                                    " x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Business Hours -->
    <section class="bg-[#0A0A0F] py-24">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mb-12 text-center">
                <div class="mb-4 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    <?php echo e(__('contact.operational_hours')); ?>

                </div>
                <h2 class="font-headline text-3xl font-bold text-[#FAFAFA] md:text-4xl">
                    <?php echo e(__('contact.business_hours')); ?>

                </h2>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="glass-card rounded-2xl p-8">
                    <div class="mb-6 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#DC2626]/10">
                            <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-headline text-xl font-bold text-[#FAFAFA]">
                            <?php echo e(__('contact.workshop_hours')); ?>

                        </h3>
                    </div>
                    <ul class="space-y-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasWorkingHours): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dayOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php ($isClosed = $workingHours[$key]['is_closed'] ?? false); ?>
                                <?php ($openTime = $workingHours[$key]['open'] ?? null); ?>
                                <?php ($closeTime = $workingHours[$key]['close'] ?? null); ?>
                                <?php ($format = ($timeFormatDisplay ?? '12') === '24' ? 'H:i' : 'g:i A'); ?>
                                <?php ($timeDisplay = $isClosed ? 'Closed' : (date($format, strtotime($openTime)) . ' – ' . date($format, strtotime($closeTime)))); ?>
                                <?php ($textClass = $isClosed ? 'text-[#DC2626]' : 'text-[#FAFAFA]'); ?>
                                <li class="flex items-center justify-between border-b border-white/5 py-3">
                                    <span class="text-[#A1A1AA]"><?php echo e($label); ?></span>
                                    <span class="<?php echo e($textClass); ?> font-semibold"><?php echo e($timeDisplay); ?></span>
                                </li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php else: ?>
                            <li class="flex items-center justify-between border-b border-white/5 py-3">
                                <span class="text-[#A1A1AA]"><?php echo e(__('contact.monday_friday')); ?></span>
                                <span class="font-semibold text-[#FAFAFA]">7:30 AM – 5:00 PM</span>
                            </li>
                            <li class="flex items-center justify-between border-b border-white/5 py-3">
                                <span class="text-[#A1A1AA]"><?php echo e(__('contact.saturday')); ?></span>
                                <span class="font-semibold text-[#FAFAFA]">8:00 AM – 1:00 PM</span>
                            </li>
                            <li class="flex items-center justify-between py-3">
                                <span class="text-[#A1A1AA]"><?php echo e(__('contact.sunday')); ?></span>
                                <span class="font-semibold text-[#DC2626]"><?php echo e(__('contact.closed')); ?></span>
                            </li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>

                <div class="glass-card relative self-start overflow-hidden rounded-2xl p-6">
                    <div class="absolute top-0 right-0 h-32 w-32 rounded-full bg-[#DC2626]/10 blur-3xl"></div>
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#DC2626]/10">
                            <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="font-headline text-xl font-bold text-[#FAFAFA]">
                            <?php echo e(__('contact.emergency_service')); ?>

                        </h3>
                    </div>
                    <p class="mb-4 text-sm text-[#A1A1AA]"><?php echo e(__('contact.emergency_description')); ?></p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($primaryPhone): ?>
                        <a
                            href="tel:<?php echo e(str_replace([' ', '-', '(', ')'], '', $primaryPhone)); ?>"
                            class="btn-premium px-4 py-2 text-sm"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span><?php echo e(__('contact.hotline')); ?>: <?php echo e($primaryPhone); ?></span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="border-t border-white/5 bg-gradient-to-b from-[#0A0A0F] to-[#121218] py-24">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mb-12 text-center">
                <div class="mb-4 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    <?php echo e(__('contact.navigation')); ?>

                </div>
                <h2 class="font-headline text-3xl font-bold text-[#FAFAFA] md:text-4xl">
                    <?php echo e(__('contact.quick_actions')); ?>

                </h2>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <a
                    href="<?php echo e(route('quote')); ?>"
                    class="glass-card group rounded-2xl p-8 transition-all hover:bg-white/[0.06]"
                >
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                        <svg class="h-6 w-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-headline mb-2 text-xl font-bold text-[#FAFAFA]"><?php echo e(__('contact.get_quote')); ?></h3>
                    <p class="mb-4 text-sm text-[#A1A1AA]"><?php echo e(__('contact.get_quote_description')); ?></p>
                    <div class="flex items-center gap-2 font-semibold text-[#DC2626]">
                        <span><?php echo e(__('contact.go')); ?></span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </a>

                <a
                    href="<?php echo e(route('services')); ?>"
                    class="glass-card group rounded-2xl p-8 transition-all hover:bg-white/[0.06]"
                >
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                        <svg class="h-6 w-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-headline mb-2 text-xl font-bold text-[#FAFAFA]">View Services</h3>
                    <p class="mb-4 text-sm text-[#A1A1AA]">Explore our range of glass services</p>
                    <div class="flex items-center gap-2 font-semibold text-[#DC2626]">
                        <span><?php echo e(__('contact.go')); ?></span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </a>

                <a
                    href="<?php echo e(route('gallery')); ?>"
                    class="glass-card group rounded-2xl p-8 transition-all hover:bg-white/[0.06]"
                >
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                        <svg class="h-6 w-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-headline mb-2 text-xl font-bold text-[#FAFAFA]">View Gallery</h3>
                    <p class="mb-4 text-sm text-[#A1A1AA]">See our completed projects</p>
                    <div class="flex items-center gap-2 font-semibold text-[#DC2626]">
                        <span><?php echo e(__('contact.go')); ?></span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </a>
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views/site/contact.blade.php ENDPATH**/ ?>