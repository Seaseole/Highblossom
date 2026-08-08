<div <?php echo e($attributes->merge(['class' => "col-{$size}"])); ?>>

    <div class="description-block">

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($icon)): ?>
            <i class="<?php echo e($icon); ?>"></i>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($title)): ?>
            <h5 class="description-header">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($url) && $urlTarget === 'title'): ?>
                    <a href="<?php echo e($url); ?>"><?php echo e($title); ?></a>
                <?php else: ?>
                    <?php echo e($title); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </h5>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($text)): ?>
            <p class="description-text">
                <span class="<?php echo e($makeTextWrapperClass()); ?>"
                    <?php if(isset($textTooltip)): ?> title="<?php echo e($textTooltip); ?>" style="cursor:help;" <?php endif; ?>>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($url) && $urlTarget === 'text'): ?>
                        <a href="<?php echo e($url); ?>"><?php echo e($text); ?></a>
                    <?php else: ?>
                        <?php echo e($text); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

</div>
<?php /**PATH C:\laragon\www\Highblossom\vendor\jeroennoten\laravel-adminlte\resources\views\components\widget\profile-col-item.blade.php ENDPATH**/ ?>