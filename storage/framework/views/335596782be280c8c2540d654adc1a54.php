<footer class="w-full border-t border-white/5 bg-[#0A0A0F]">
    
    <div class="mx-auto max-w-[1400px] px-6 py-16 lg:px-8 lg:py-20">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-4 lg:gap-12">
            
            <div class="lg:col-span-1">
                <a href="<?php echo e(route('home')); ?>" class="transition-opacity hover:opacity-80">
                    <?php if (isset($component)) { $__componentOriginalec02c762cb8fdc609b31e0340efa6a92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalec02c762cb8fdc609b31e0340efa6a92 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo-trust-badge','data' => ['businessName' => $logoText,'iconSrc' => $businessLogo ? Storage::url($businessLogo) : null,'fontSize' => 'text-xl font-bold font-headline','showBadge' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logo-trust-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['business-name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($logoText),'icon-src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($businessLogo ? Storage::url($businessLogo) : null),'font-size' => 'text-xl font-bold font-headline','show-badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalec02c762cb8fdc609b31e0340efa6a92)): ?>
<?php $attributes = $__attributesOriginalec02c762cb8fdc609b31e0340efa6a92; ?>
<?php unset($__attributesOriginalec02c762cb8fdc609b31e0340efa6a92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalec02c762cb8fdc609b31e0340efa6a92)): ?>
<?php $component = $__componentOriginalec02c762cb8fdc609b31e0340efa6a92; ?>
<?php unset($__componentOriginalec02c762cb8fdc609b31e0340efa6a92); ?>
<?php endif; ?>
                </a>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-[#A1A1AA]">
                    Premium automotive glass repair, restoration, and replacement for Gaborone's vehicle owners and
                    commercial fleets.
                </p>
                <div class="mt-6 flex items-center gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($primaryPhone): ?>
                        <a
                            href="tel:<?php echo e(str_replace([' ', '-', '(', ')'], '', $primaryPhone)); ?>"
                            class="text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                            aria-label="Call us"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($primaryEmail): ?>
                        <a
                            href="mailto:<?php echo e($primaryEmail); ?>"
                            class="text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                            aria-label="Email us"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="mt-4 flex items-center gap-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facebookUrl): ?>
                        <a
                            href="<?php echo e($facebookUrl); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                            title="Facebook"
                        >
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($instagramUrl): ?>
                        <a
                            href="<?php echo e($instagramUrl); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                            title="Instagram"
                        >
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($linkedinUrl): ?>
                        <a
                            href="<?php echo e($linkedinUrl); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                            title="LinkedIn"
                        >
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                            </svg>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div>
                <h4 class="font-headline mb-6 text-sm font-semibold tracking-wide text-[#FAFAFA] uppercase">
                    Services
                </h4>
                <ul class="space-y-3">
                    <li>
                        <a
                            href="<?php echo e(route('services')); ?>"
                            class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                        >
                            Windscreen Replacement
                        </a>
                    </li>
                    <li>
                        <a
                            href="<?php echo e(route('services')); ?>"
                            class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                        >
                            Side & Rear Windows
                        </a>
                    </li>
                    <li>
                        <a
                            href="<?php echo e(route('services')); ?>"
                            class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                        >
                            Heavy Machinery
                        </a>
                    </li>
                    <li>
                        <a
                            href="<?php echo e(route('services')); ?>"
                            class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                        >
                            Fleet Services
                        </a>
                    </li>
                    <li>
                        <a
                            href="<?php echo e(route('services')); ?>"
                            class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                        >
                            Mobile Service
                        </a>
                    </li>
                </ul>
            </div>

            
            <div>
                <h4 class="font-headline mb-6 text-sm font-semibold tracking-wide text-[#FAFAFA] uppercase">Company</h4>
                <ul class="space-y-3">
                    <li>
                        <a
                            href="<?php echo e(route('home')); ?>"
                            class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                        >
                            Home
                        </a>
                    </li>
                    <li>
                        <a
                            href="<?php echo e(route('gallery')); ?>"
                            class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                        >
                            Our Work
                        </a>
                    </li>
                    <li>
                        
                    </li>
                    <li>
                        <a
                            href="<?php echo e(route('quote')); ?>"
                            class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                        >
                            Get a Quote
                        </a>
                    </li>
                    <li>
                        <a
                            href="<?php echo e(route('contact')); ?>"
                            class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                        >
                            Contact Us
                        </a>
                    </li>
                </ul>
            </div>

            
            <div>
                <h4 class="font-headline mb-6 text-sm font-semibold tracking-wide text-[#FAFAFA] uppercase">Contact</h4>
                <div class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($primaryPhone): ?>
                        <div class="glass-card rounded-xl p-4">
                            <div class="mb-1 text-xs font-semibold tracking-wider text-[#DC2626] uppercase">Phone</div>
                            <a
                                href="tel:<?php echo e(str_replace([' ', '-', '(', ')'], '', $primaryPhone)); ?>"
                                class="font-headline font-semibold text-[#FAFAFA] transition-colors hover:text-[#DC2626]"
                            >
                                <?php echo e($primaryPhone); ?>

                            </a>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="glass-card rounded-xl p-4">
                        <div class="mb-1 text-xs font-semibold tracking-wider text-[#DC2626] uppercase">Location</div>
                        <p class="text-sm leading-relaxed text-[#FAFAFA]"><?php echo e($companyAddress); ?></p>
                    </div>

                    <div class="glass-card rounded-xl p-4">
                        <div class="mb-1 text-xs font-semibold tracking-wider text-[#DC2626] uppercase">Hours</div>
                        <p class="text-sm text-[#FAFAFA]">
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

                                        echo implode('<br>', $formatted);
                                    } else {
                                        echo 'Mon–Fri · 7:30 AM – 5:00 PM<br>Sat · 8:00 AM – 1:00 PM<br>Sun · Closed';
                                    }
                                } catch (\Exception $e) {
                                    echo 'Mon–Fri · 7:30 AM – 5:00 PM<br>Sat · 8:00 AM – 1:00 PM<br>Sun · Closed';
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="border-t border-white/5">
        <div class="mx-auto max-w-[1400px] px-6 py-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
                <p class="text-sm text-[#71717A]">© <?php echo e(date('Y')); ?> <?php echo e($companyName); ?>. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a
                        href="<?php echo e(route('privacy')); ?>"
                        class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                    >
                        Privacy Policy
                    </a>
                    <a
                        href="<?php echo e(route('terms')); ?>"
                        class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                    >
                        Terms of Service
                    </a>
                </div>
            </div>
            <div class="mt-6 flex justify-center border-t border-white/5 pt-6">
                <span class="flex items-center gap-2 text-sm text-[#71717A]">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                    Crafted with care by
                    <a
                        href="https://highblossom.com"
                        class="font-medium text-[#A1A1AA] transition-colors duration-200 hover:text-[#DC2626]"
                    >Eugene Seaseole</a>
                </span>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\partials\site-footer.blade.php ENDPATH**/ ?>