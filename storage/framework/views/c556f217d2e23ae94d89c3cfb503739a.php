<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'show' => false,
    'maxWidth' => '2xl',
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
    'show' => false,
    'maxWidth' => '2xl',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $maxWidthClasses = match ($maxWidth) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        default => 'max-w-2xl',
    };
?>

<div
    x-data="{ show: <?php echo \Illuminate\Support\Js::from($show)->toHtml() ?> }"
    x-show="show"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    class="relative z-[100]"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    style="display: none"
>
    <!-- Backdrop with advanced blur -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-[#0A0A0F]/80 backdrop-blur-md transition-opacity"
        aria-hidden="true"
    ></div>

    <!-- Modal panel -->
    <div class="fixed inset-0 z-[101] overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                x-show="show"
                x-on:click.away="show = false"
                x-transition:enter="ease-out-expo duration-500"
                x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in-out-quint duration-300"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-[2rem] bg-[#16161D] border border-white/10 text-left shadow-[0_32px_64px_-16px_rgba(0,0,0,0.6)] transition-all sm:my-8 sm:w-full <?php echo e($maxWidthClasses); ?>"
            >
                <!-- Feather Overlay Effect -->
                <div class="feather-overlay absolute inset-0 rounded-[2rem]"></div>

                <div class="relative z-10"><?php echo e($slot); ?></div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\ui\modal.blade.php ENDPATH**/ ?>