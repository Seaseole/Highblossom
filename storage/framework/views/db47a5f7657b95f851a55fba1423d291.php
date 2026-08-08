<?php if (isset($component)) { $__componentOriginal501803f3e4defcbbeaedee798b98ded4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal501803f3e4defcbbeaedee798b98ded4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::admin','data' => ['title' => ''.e(__('admin-bookings.title')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e(__('admin-bookings.title')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mx-auto max-w-7xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-center">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                    <?php echo e(__('admin-bookings.title')); ?>

                </h1>
                <p class="text-gray-500 dark:text-gray-400">Manage and track customer bookings.</p>
            </div>

            <form action="<?php echo e(route('admin.bookings.index')); ?>" method="GET" class="flex items-center gap-3">
                <input
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search client..."
                    class="rounded-full border border-gray-200 bg-white px-4 py-2 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-[var(--color-admin-accent)]"
                />
                <select
                    name="status"
                    class="rounded-full border border-gray-200 bg-white px-4 py-2 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-[var(--color-admin-accent)]"
                >
                    <option value="">All Statuses</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['pending', 'confirmed', 'completed', 'cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($status); ?>" <?php echo e(request('status') === $status ? 'selected' : ''); ?>>
                            <?php echo e(ucfirst($status)); ?>

                        </option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
                <button
                    type="submit"
                    class="rounded-full bg-gray-100 px-6 py-2 text-sm font-medium text-gray-900 transition-all hover:bg-gray-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10"
                >
                    Filter
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            <?php echo e(__('admin-bookings.client')); ?>

                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Scheduled
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            <?php echo e(__('admin-bookings.contact')); ?>

                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            <?php echo e(__('admin-bookings.vehicle')); ?>

                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            <?php echo e(__('admin-bookings.price')); ?>

                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            <?php echo e(__('admin-bookings.status')); ?>

                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            <?php echo e(__('admin-bookings.actions')); ?>

                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    <?php echo e($booking->client_name); ?>

                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->user): ?>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <?php echo e($booking->user->email); ?>

                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->inspection): ?>
                                    <div class="mt-1">
                                        <span class="rounded bg-indigo-100 px-2 py-0.5 text-[10px] font-medium text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">Has Inspection</span>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                <?php echo e($booking->scheduled_at ? $booking->scheduled_at->format('M j, Y g:i A') : 'TBC'); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                <?php echo e($booking->client_phone ?? '-'); ?><br />
                                <?php echo e($booking->client_email ?? '-'); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                <?php echo e($booking->vehicle_details); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <?php echo e(number_format($booking->total_price, 2)); ?>

                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $statusColors = [
                                        'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                        'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400',
                                    ];
                                ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                                    <?php echo e(ucfirst($booking->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="<?php echo e(route('admin.bookings.show', $booking)); ?>"
                                    class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white"
                                >
                                    <?php echo e(__('admin-bookings.view')); ?>

                                </a>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <?php echo e(__('admin-bookings.no_bookings_found')); ?>

                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4"><?php echo e($bookings->links()); ?></div>
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views/admin/bookings/index.blade.php ENDPATH**/ ?>