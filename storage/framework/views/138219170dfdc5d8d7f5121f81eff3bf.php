<?php if (isset($component)) { $__componentOriginal7d53c0f8d784d0b9ac83f5715b3fac6f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7d53c0f8d784d0b9ac83f5715b3fac6f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.minimal','data' => ['status' => '428','title' => 'Precondition Required','description' => 'The server requires the request to be conditional. Please include the required preconditions.','actionText' => 'Go Home','actionUrl' => '/']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('minimal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => '428','title' => 'Precondition Required','description' => 'The server requires the request to be conditional. Please include the required preconditions.','actionText' => 'Go Home','actionUrl' => '/']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7d53c0f8d784d0b9ac83f5715b3fac6f)): ?>
<?php $attributes = $__attributesOriginal7d53c0f8d784d0b9ac83f5715b3fac6f; ?>
<?php unset($__attributesOriginal7d53c0f8d784d0b9ac83f5715b3fac6f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7d53c0f8d784d0b9ac83f5715b3fac6f)): ?>
<?php $component = $__componentOriginal7d53c0f8d784d0b9ac83f5715b3fac6f; ?>
<?php unset($__componentOriginal7d53c0f8d784d0b9ac83f5715b3fac6f); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\errors\428.blade.php ENDPATH**/ ?>