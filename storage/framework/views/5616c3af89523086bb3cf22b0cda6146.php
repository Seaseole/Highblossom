<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'module',
    'value' => null,
    'currentId' => null,
    'scope' => [],
    'scopeInputs' => [],
    'auto' => false,
    'label' => 'Sort Order',
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
    'module',
    'value' => null,
    'currentId' => null,
    'scope' => [],
    'scopeInputs' => [],
    'auto' => false,
    'label' => 'Sort Order',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $modelClass = App\Support\SortOrderRegistry::model($module);
    $column = (new $modelClass)->sortOrderColumn();
    $previous = old($column);
    $next = $modelClass::nextSortOrder($scope);
    $display = $auto ? '' : (filled($previous) ? $previous : ($value ?? $next));
    $checkUrl = route('admin.sort-order.availability', array_filter(
        ['module' => $module, 'scope' => $scope],
        fn ($value) => $value !== [] && $value !== null
    ));
?>


<div data-sort-order-field>
    <label
        for="<?php echo e($column); ?>-input"
        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
    ><?php echo e($label); ?></label>

    <input
        type="number"
        id="<?php echo e($column); ?>-input"
        name="<?php echo e($column); ?>"
        value="<?php echo e($display); ?>"
        min="1"
        step="1"
        inputmode="numeric"
        data-sort-check-url="<?php echo e($checkUrl); ?>"
        data-sort-current="<?php echo e($currentId); ?>"
        data-sort-scope-inputs="<?php echo e(implode(',', array_map(
            fn (string $field, string $column) => "{$column}:{$field}",
            $scopeInputs,
            array_keys($scopeInputs),
        ))); ?>"
        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
    />

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($auto): ?>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Left blank, the next free number for the parent selected above is assigned
            automatically. Deactivating a record never frees its number.
        </p>
    <?php else: ?>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Next free number is <?php echo e($next); ?>. Leave the field blank to take it automatically;
            deactivating a record never frees its number.
        </p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <p
        data-sort-error
        class="mt-2 text-sm font-medium text-red-600 dark:text-red-400"
        <?php if(! $errors->has($column)): ?> hidden <?php endif; ?>
    ><?php echo e($errors->first($column)); ?></p>
</div>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/components/admin/sort-order-field.blade.php ENDPATH**/ ?>