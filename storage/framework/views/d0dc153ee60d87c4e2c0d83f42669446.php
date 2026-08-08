

<?php $iconTrailing ??= $attributes->pluck('icon:trailing'); ?>
<?php $iconVariant ??= $attributes->pluck('icon:variant'); ?>

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'iconVariant' => 'outline',
    'iconTrailing' => null,
    'expandable' => false,
    'expanded' => true,
    'heading' => null,
    'icon' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'iconVariant' => 'outline',
    'iconTrailing' => null,
    'expandable' => false,
    'expanded' => true,
    'heading' => null,
    'icon' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if ($expandable && $heading) { ?>
<?php if ($icon) { ?>
<ui-disclosure
    <?php echo e($attributes->class('group/disclosure in-data-flux-sidebar-collapsed-desktop:hidden')); ?>

    <?php if($expanded === true): ?> open <?php endif; ?>
    data-flux-sidebar-group
>
    <button
        type="button"
        class="group/disclosure-button my-px flex h-8 w-full items-center rounded-lg border-1 border-transparent text-zinc-500 hover:bg-zinc-800/5 hover:text-zinc-800 in-data-flux-sidebar-on-mobile:h-10 dark:text-white/80 dark:hover:bg-white/[7%] dark:hover:text-white"
    >
        <div class="px-3">
            <?php if (is_string($icon) && $icon !== '') { ?>
            <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => $icon,'variant' => $iconVariant,'class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($iconVariant),'class' => 'size-4']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $attributes = $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $component = $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
            <?php } else { ?>
            <?php echo e($icon); ?>

            <?php } ?>
        </div>

        <span class="flex-1 text-left text-sm leading-none font-medium rtl:text-right"><?php echo e($heading); ?></span>

        <div class="ps-3 pe-2.5">
            <?php if (isset($component)) { $__componentOriginal298ff21bbc41cebb188cbb18c6c11bc0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal298ff21bbc41cebb188cbb18c6c11bc0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-down','data' => ['class' => 'hidden size-3! group-data-open/disclosure-button:block']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'hidden size-3! group-data-open/disclosure-button:block']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal298ff21bbc41cebb188cbb18c6c11bc0)): ?>
<?php $attributes = $__attributesOriginal298ff21bbc41cebb188cbb18c6c11bc0; ?>
<?php unset($__attributesOriginal298ff21bbc41cebb188cbb18c6c11bc0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal298ff21bbc41cebb188cbb18c6c11bc0)): ?>
<?php $component = $__componentOriginal298ff21bbc41cebb188cbb18c6c11bc0; ?>
<?php unset($__componentOriginal298ff21bbc41cebb188cbb18c6c11bc0); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-right','data' => ['class' => 'block size-3! group-data-open/disclosure-button:hidden rtl:rotate-180']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'block size-3! group-data-open/disclosure-button:hidden rtl:rotate-180']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $attributes = $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $component = $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
        </div>
    </button>

    <div class="relative hidden ps-7 data-open:block" <?php if($expanded === true): ?> data-open <?php endif; ?>>
        <div class="absolute inset-y-[3px] start-0 ms-5 w-px bg-zinc-200 dark:bg-white/30"></div>

        <div class="flex flex-col"><?php echo e($slot); ?></div>
    </div>
</ui-disclosure>

<?php if (isset($component)) { $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::dropdown','data' => ['hover' => true,'class' => 'not-in-data-flux-sidebar-collapsed-desktop:hidden in-data-flux-sidebar-on-mobile:hidden','position' => 'right','align' => 'start','dataFluxSidebarGroupDropdown' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hover' => true,'class' => 'not-in-data-flux-sidebar-collapsed-desktop:hidden in-data-flux-sidebar-on-mobile:hidden','position' => 'right','align' => 'start','data-flux-sidebar-group-dropdown' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <button
        type="button"
        class="group/disclosure-button my-px flex h-8 w-full items-center gap-3 rounded-lg border-1 border-transparent px-3 text-zinc-500 hover:bg-zinc-800/5 hover:text-zinc-800 in-data-flux-menu:px-2 in-data-flux-menu:text-zinc-800 in-data-flux-menu:hover:bg-zinc-50 in-data-flux-sidebar-collapsed-desktop:not-in-data-flux-menu:w-10 in-data-flux-sidebar-collapsed-desktop:not-in-data-flux-menu:justify-center dark:text-white/80 dark:hover:bg-white/[7%] dark:hover:text-white in-data-flux-menu:dark:text-white dark:in-data-flux-menu:hover:bg-zinc-600"
    >
        <?php if ($icon) { ?>
        <div class="relative">
            <?php if (is_string($icon) && $icon !== '') { ?>
            <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => $icon,'variant' => $iconVariant,'class' => 'in-data-flux-menu:[[data-flux-sidebar-group-dropdown]>button:hover_&]:text-current size-4 in-data-flux-menu:text-zinc-400 in-data-flux-menu:dark:text-white/80']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($iconVariant),'class' => 'in-data-flux-menu:[[data-flux-sidebar-group-dropdown]>button:hover_&]:text-current size-4 in-data-flux-menu:text-zinc-400 in-data-flux-menu:dark:text-white/80']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $attributes = $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $component = $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
            <?php } else { ?>
            <?php echo e($icon); ?>

            <?php } ?>
        </div>
        <?php } ?>

        <span class="hidden flex-1 text-start text-sm leading-none font-medium text-zinc-800 in-data-flux-menu:block dark:text-white"><?php echo e($heading); ?></span>

        <div class="hidden in-data-flux-menu:block">
            <?php if (isset($component)) { $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-right','data' => ['variant' => $iconVariant,'class' => '[[data-flux-sidebar-group-dropdown]>button:hover_&]:text-current ms-auto size-4 text-zinc-400 rtl:hidden']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($iconVariant),'class' => '[[data-flux-sidebar-group-dropdown]>button:hover_&]:text-current ms-auto size-4 text-zinc-400 rtl:hidden']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $attributes = $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $component = $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal93e8a1cf63877447e3f60f50005ff258 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal93e8a1cf63877447e3f60f50005ff258 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-left','data' => ['variant' => $iconVariant,'class' => '[[data-flux-sidebar-group-dropdown]>button:hover_&]:text-current ms-auto hidden size-4 text-zinc-400 rtl:inline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($iconVariant),'class' => '[[data-flux-sidebar-group-dropdown]>button:hover_&]:text-current ms-auto hidden size-4 text-zinc-400 rtl:inline']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal93e8a1cf63877447e3f60f50005ff258)): ?>
<?php $attributes = $__attributesOriginal93e8a1cf63877447e3f60f50005ff258; ?>
<?php unset($__attributesOriginal93e8a1cf63877447e3f60f50005ff258); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal93e8a1cf63877447e3f60f50005ff258)): ?>
<?php $component = $__componentOriginal93e8a1cf63877447e3f60f50005ff258; ?>
<?php unset($__componentOriginal93e8a1cf63877447e3f60f50005ff258); ?>
<?php endif; ?>
        </div>
    </button>

    <?php if (isset($component)) { $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <?php if (isset($component)) { $__componentOriginald8634b0991833885e8edc0e72be89375 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8634b0991833885e8edc0e72be89375 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.group','data' => ['heading' => $heading]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($heading)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
 <?php echo e($slot); ?>  <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8634b0991833885e8edc0e72be89375)): ?>
<?php $attributes = $__attributesOriginald8634b0991833885e8edc0e72be89375; ?>
<?php unset($__attributesOriginald8634b0991833885e8edc0e72be89375); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8634b0991833885e8edc0e72be89375)): ?>
<?php $component = $__componentOriginald8634b0991833885e8edc0e72be89375; ?>
<?php unset($__componentOriginald8634b0991833885e8edc0e72be89375); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $attributes = $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $component = $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $attributes = $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $component = $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
<?php } else { ?>
<ui-disclosure
    <?php echo e($attributes->class('group/disclosure in-data-flux-sidebar-collapsed-desktop:hidden')); ?>

    <?php if($expanded === true): ?> open <?php endif; ?>
    data-flux-sidebar-group
>
    <button
        type="button"
        class="group/disclosure-button my-px flex h-8 w-full items-center rounded-lg border-1 border-transparent text-zinc-500 hover:bg-zinc-800/5 hover:text-zinc-800 in-data-flux-sidebar-on-mobile:h-10 dark:text-white/80 dark:hover:bg-white/[7%] dark:hover:text-white"
    >
        <div class="ps-3.5 pe-3.5">
            <?php if (isset($component)) { $__componentOriginal298ff21bbc41cebb188cbb18c6c11bc0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal298ff21bbc41cebb188cbb18c6c11bc0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-down','data' => ['class' => 'hidden size-3! group-data-open/disclosure-button:block']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'hidden size-3! group-data-open/disclosure-button:block']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal298ff21bbc41cebb188cbb18c6c11bc0)): ?>
<?php $attributes = $__attributesOriginal298ff21bbc41cebb188cbb18c6c11bc0; ?>
<?php unset($__attributesOriginal298ff21bbc41cebb188cbb18c6c11bc0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal298ff21bbc41cebb188cbb18c6c11bc0)): ?>
<?php $component = $__componentOriginal298ff21bbc41cebb188cbb18c6c11bc0; ?>
<?php unset($__componentOriginal298ff21bbc41cebb188cbb18c6c11bc0); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-right','data' => ['class' => 'block size-3! group-data-open/disclosure-button:hidden rtl:rotate-180']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'block size-3! group-data-open/disclosure-button:hidden rtl:rotate-180']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $attributes = $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $component = $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
        </div>

        <span class="text-sm leading-none font-medium"><?php echo e($heading); ?></span>
    </button>

    <div class="relative hidden ps-7 data-open:block" <?php if($expanded === true): ?> data-open <?php endif; ?>>
        <div class="absolute inset-y-[3px] start-0 ms-5 w-px bg-zinc-200 dark:bg-white/30"></div>

        <div class="flex flex-col"><?php echo e($slot); ?></div>
    </div>
</ui-disclosure>
<?php } ?>

<?php } elseif ($heading) { ?>
<div <?php echo e($attributes->class('flex flex-col in-data-flux-sidebar-collapsed-desktop:hidden')); ?> data-flux-sidebar-group>
    <div class="px-3 py-2">
        <div class="text-sm leading-none font-medium text-zinc-400"><?php echo e($heading); ?></div>
    </div>

    <div class="flex flex-col"><?php echo e($slot); ?></div>
</div>
<?php } else { ?>
<div <?php echo e($attributes->class('flex flex-col in-data-flux-sidebar-collapsed-desktop:hidden')); ?> data-flux-sidebar-group>
    <?php echo e($slot); ?>

</div>
<?php } ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\flux\sidebar\group.blade.php ENDPATH**/ ?>