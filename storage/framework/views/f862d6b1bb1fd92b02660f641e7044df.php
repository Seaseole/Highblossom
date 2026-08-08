

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'position' => 'bottom end',
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
    'position' => 'bottom end',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<ui-toast
    x-data
    x-on:toast-show.document="! $el.closest('ui-toast-group') && $el.showToast($event.detail)"
    popover="manual"
    position="<?php echo e($position); ?>"
    wire:ignore
>
    <template>
        <div
            <?php echo e($attributes->only(['class'])->class('max-w-sm in-[ui-toast-group]:max-w-auto in-[ui-toast-group]:w-xs sm:in-[ui-toast-group]:w-sm')); ?>

            data-variant=""
            data-flux-toast-dialog
        >
            <div class="flex rounded-xl border border-zinc-200 border-b-zinc-300/80 bg-white p-2 shadow-lg dark:border-zinc-600 dark:bg-zinc-700">
                <div class="flex flex-1 items-start gap-4 overflow-hidden">
                    <div class="flex flex-1 gap-2 py-1.5 ps-2.5">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="[[data-flux-toast-dialog][data-variant=success]_&]:block mt-0.5 hidden size-4 shrink-0 text-lime-600 dark:text-lime-400">
                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm3.844-8.791a.75.75 0 0 0-1.188-.918l-3.7 4.79-1.649-1.833a.75.75 0 1 0-1.114 1.004l2.25 2.5a.75.75 0 0 0 1.15-.043l4.25-5.5Z" clip-rule="evenodd" />
                        </svg>

                        
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="[[data-flux-toast-dialog][data-variant=warning]_&]:block mt-0.5 hidden size-4 shrink-0 text-amber-500 dark:text-amber-400">
                            <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                        </svg>

                        
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="[[data-flux-toast-dialog][data-variant=danger]_&]:block mt-0.5 hidden size-4 shrink-0 text-rose-500 dark:text-rose-400">
                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                        </svg>

                        <div>
                            
                            <div class="[&:not(:empty)+div]:font-normal [&:not(:empty)+div]:text-zinc-500 [&:not(:empty)+div]:dark:text-zinc-300 [&:not(:empty)]:pb-2 text-sm font-medium text-zinc-800 dark:text-white">
                                <slot name="heading"></slot>
                            </div>

                            
                            <div class="text-sm font-medium text-zinc-800 dark:text-white">
                                <slot name="text"></slot>
                            </div>
                        </div>
                    </div>

                    
                    <ui-close class="flex items-center">
                        <button
                            type="button"
                            class="inline-flex h-8 w-8 items-center justify-center gap-2 truncate rounded-md bg-transparent text-sm font-medium text-zinc-400 hover:bg-zinc-800/5 hover:text-zinc-800 disabled:cursor-default disabled:opacity-50 dark:text-zinc-400 dark:hover:bg-white/15 dark:hover:text-white dark:disabled:opacity-75"
                            as="button"
                        >
                            <div>
                                <svg class="[:where(&)]:size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"></path>
                                </svg>
                            </div>
                        </button>
                    </ui-close>
                </div>
            </div>
        </div>
    </template>
</ui-toast>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\flux\toast\index.blade.php ENDPATH**/ ?>