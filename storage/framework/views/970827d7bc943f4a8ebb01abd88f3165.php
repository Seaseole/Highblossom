<?php
    $items = $items ?? [];
    $multipleOpen = $multiple_open ?? false;
    $accordionId = 'cb-accordion-'.uniqid();
?>

<div
    class="cb-accordion"
    data-cb-accordion-id="<?php echo e($accordionId); ?>"
    data-cb-accordion-multiple="<?php echo e($multipleOpen ? 'true' : 'false'); ?>"
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <?php
            $itemId = $accordionId.'-'.$index;
        ?>
        <details
            class="cb-accordion__item"
            data-cb-accordion-item="<?php echo e($itemId); ?>"
            <?php if(! $multipleOpen): ?> data-cb-accordion-exclusive <?php endif; ?>
        >
            <summary class="cb-accordion__header">
                <span class="cb-accordion__title"><?php echo e($item['title'] ?? ''); ?></span>
                <span class="cb-accordion__icon" aria-hidden="true">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </span>
            </summary>
            <div class="cb-accordion__content"><?php echo $item['content'] ?? ''; ?></div>
        </details>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
    <script>
        (function () {
            if (typeof window.cbAccordionInit === 'function') return;

            window.cbAccordionInit = function () {
                document.querySelectorAll('[data-cb-accordion-id]').forEach(function (accordion) {
                    var accordionId = accordion.getAttribute('data-cb-accordion-id');
                    var multipleOpen = accordion.getAttribute('data-cb-accordion-multiple') === 'true';

                    accordion.querySelectorAll('[data-cb-accordion-item]').forEach(function (item) {
                        var summary = item.querySelector('summary');

                        summary.addEventListener('click', function (e) {
                            if (!multipleOpen) {
                                accordion.querySelectorAll('[data-cb-accordion-item]').forEach(function (otherItem) {
                                    if (otherItem !== item) {
                                        otherItem.removeAttribute('open');
                                    }
                                });
                            }
                        });
                    });
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', window.cbAccordionInit);
            } else {
                window.cbAccordionInit();
            }
        })();
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\Highblossom\packages\ContentBlocks\resources\views\accordion.blade.php ENDPATH**/ ?>