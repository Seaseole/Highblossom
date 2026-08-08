<?php if (isset($component)) { $__componentOriginal501803f3e4defcbbeaedee798b98ded4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal501803f3e4defcbbeaedee798b98ded4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::admin','data' => ['title' => 'Booking Details']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Booking Details']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <a
                    href="<?php echo e(route('admin.bookings.index')); ?>"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >
                    ← Back to Bookings
                </a>
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                    Booking #<?php echo e($booking->id); ?>

                </h1>
                <p class="text-gray-500 dark:text-gray-400">
                    Created on <?php echo e($booking->created_at->format('F j, Y \a\t g:i A')); ?>

                </p>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="space-y-8 lg:col-span-2">
                <!-- Client Information -->
                <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Client Information</h2>
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Name</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo e($booking->client_name); ?>

                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Email</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo e($booking->client_email ?? '—'); ?>

                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Phone</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo e($booking->client_phone ?? '—'); ?>

                            </dd>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->location === 'mobile' && $booking->client_address): ?>
                            <div class="space-y-1">
                                <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                    Client Address
                                </dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    <?php echo e($booking->client_address); ?>

                                </dd>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->user): ?>
                            <div class="space-y-1">
                                <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                    User Account
                                </dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    <?php echo e($booking->user->email); ?>

                                </dd>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Scheduled At</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo e($booking->scheduled_at ? $booking->scheduled_at->format('F j, Y g:i A') : 'TBC'); ?>

                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Location</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo e($booking->location === 'mobile' ? 'Mobile Service' : ($booking->location ? 'Workshop' : 'TBC')); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->location === 'mobile' && $booking->client_address): ?>
                                    <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400"><?php echo e($booking->client_address); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Vehicle Details -->
                <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Vehicle Details</h2>
                    <p class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-sm text-gray-600 dark:border-white/5 dark:bg-white/5 dark:text-gray-300">
                        <?php echo e($booking->vehicle_details); ?>

                    </p>
                </div>

                <!-- Inspection Panel -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Inspection</h2>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->inspection): ?>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        Inspection #<?php echo e($booking->inspection->id); ?>

                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Assigned to: <?php echo e($booking->inspection->staff->name ?? 'Unassigned'); ?>

                                    </p>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($booking->inspection->ended_at ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400'); ?>">
                                    <?php echo e($booking->inspection->ended_at ? 'Completed' : 'Scheduled'); ?>

                                </span>
                            </div>
                            <a
                                href="<?php echo e(route('admin.inspections.show', $booking->inspection)); ?>"
                                class="inline-block w-full rounded-full bg-gray-900 px-6 py-2.5 text-center text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                            >
                                View Inspection
                            </a>
                        </div>
                    <?php else: ?>
                        <form action="<?php echo e(route('admin.inspections.store')); ?>" method="POST" class="space-y-4">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="booking_id" value="<?php echo e($booking->id); ?>" />
                            <input type="hidden" name="scheduled_at" value="<?php echo e($booking->scheduled_at); ?>" />
                            <input type="hidden" name="location" value="<?php echo e($booking->location ?? 'mobile'); ?>" />
                            <input type="hidden" name="type" value="mobile" />
                            <!-- Default to mobile, you could add a selector if needed -->

                            <div class="space-y-1">
                                <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Assign Staff</label>
                                <select
                                    name="staff_id"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                >
                                    <option value="">Select Staff</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\User::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['staff_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <button
                                type="submit"
                                class="w-full rounded-full bg-blue-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-blue-700"
                            >
                                Schedule Inspection
                            </button>
                        </form>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Status Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status</h2>
                    <?php
                        $statusColors = [
                            'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                            'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                            'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                            'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400',
                        ];
                    ?>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium <?php echo e($statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                        <?php echo e(ucfirst($booking->status)); ?>

                    </span>

                    <form
                        action="<?php echo e(route('admin.bookings.update-status', $booking)); ?>"
                        method="POST"
                        class="space-y-4 border-t border-gray-100 pt-4 dark:border-white/10"
                    >
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <select
                            name="status"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        >
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['pending', 'confirmed', 'completed', 'cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($status); ?>" <?php echo e($booking->status === $status ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($status)); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <button
                            type="submit"
                            class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                        >
                            Update Status
                        </button>
                    </form>
                </div>

                <!-- Pricing & Notes Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pricing & Notes</h2>
                    <form action="<?php echo e(route('admin.bookings.update', $booking)); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Total Price ($)</label>
                            <input
                                type="number"
                                step="0.01"
                                name="total_price"
                                value="<?php echo e(old('total_price', $booking->total_price)); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            />
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Internal Notes</label>
                            <!-- Note: The notes column might not exist in the database, this is handled gracefully if not fillable -->
                            <textarea
                                name="notes"
                                rows="3"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            ><?php echo e(old('notes', $booking->notes ?? '')); ?></textarea>
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-full bg-gray-100 px-6 py-2.5 text-sm font-medium text-gray-900 transition-all hover:bg-gray-200 dark:bg-white/10 dark:text-white dark:hover:bg-white/20"
                        >
                            Save Changes
                        </button>
                    </form>
                </div>

                <!-- Delete Action -->
                <form
                    action="<?php echo e(route('admin.bookings.destroy', $booking)); ?>"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this booking?');"
                >
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button
                        type="submit"
                        class="w-full text-sm font-medium text-red-600 transition-opacity hover:opacity-75 dark:text-red-400"
                    >
                        Delete Booking
                    </button>
                </form>
            </div>
        </div>
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views/admin/bookings/show.blade.php ENDPATH**/ ?>