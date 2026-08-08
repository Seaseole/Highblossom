<?php if (isset($component)) { $__componentOriginal501803f3e4defcbbeaedee798b98ded4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal501803f3e4defcbbeaedee798b98ded4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::admin','data' => ['title' => 'Message Details']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Message Details']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <a
                    href="<?php echo e(route('admin.contact-messages.index')); ?>"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >
                    ← Back to Messages
                </a>
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                    <?php echo e($message->subject); ?>

                </h1>
                <p class="text-gray-500 dark:text-gray-400">
                    Received <?php echo e($message->created_at->format('F j, Y \a\t g:i A')); ?>

                </p>
            </div>

            <span class="px-3 py-1 rounded-full text-sm font-medium <?php echo e($message->is_read ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400'); ?>">
                <?php echo e($message->is_read ? 'Read' : 'Unread'); ?>

            </span>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="space-y-8 lg:col-span-2">
                <!-- Sender Information -->
                <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Sender Information</h2>
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Name</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($message->name); ?></dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Email</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <a
                                    href="mailto:<?php echo e($message->email); ?>"
                                    class="text-blue-600 hover:underline dark:text-blue-400"
                                ><?php echo e($message->email); ?></a>
                            </dd>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($message->phone): ?>
                            <div class="space-y-1">
                                <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Phone</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    <a
                                        href="tel:<?php echo e($message->phone); ?>"
                                        class="text-blue-600 hover:underline dark:text-blue-400"
                                    ><?php echo e($message->phone); ?></a>
                                </dd>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </dl>
                </div>

                <!-- Message Content -->
                <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Message</h2>
                    <p class="rounded-2xl border border-gray-100 bg-gray-50 p-6 text-sm leading-relaxed text-gray-600 dark:border-white/5 dark:bg-white/5 dark:text-gray-300">
                        <?php echo e($message->message); ?>

                    </p>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Actions Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Actions</h2>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $message->is_read): ?>
                        <form action="<?php echo e(route('admin.contact-messages.mark-read', $message)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button
                                type="submit"
                                class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                            >
                                Mark as Read
                            </button>
                        </form>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <a
                        href="mailto:<?php echo e($message->email); ?>?subject=Re: <?php echo e($message->subject); ?>"
                        class="inline-block w-full rounded-full bg-gray-100 px-6 py-2.5 text-center text-sm font-medium text-gray-900 transition-all hover:bg-gray-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10"
                    >
                        Reply via Email
                    </a>
                </div>

                <!-- Delete Action -->
                <form
                    action="<?php echo e(route('admin.contact-messages.destroy', $message)); ?>"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this message?');"
                >
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button
                        type="submit"
                        class="w-full text-sm font-medium text-red-600 transition-opacity hover:opacity-75 dark:text-red-400"
                    >
                        Delete Message
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views\admin\contact-messages\show.blade.php ENDPATH**/ ?>