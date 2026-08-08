<div class="bg-white shadow-md rounded-md border border-gray-200 mt-6">
    <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Extra Stats</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left divide-y divide-gray-200">
            <tbody class="divide-y divide-gray-100">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $extraStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extraStatKey => $extraStatValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td class="px-4 py-2 font-normal w-1/3"><?php echo e($extraStatKey); ?></td>
                    <td class="px-4 py-2 text-gray-500 text-right float-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_bool($extraStatValue)): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($extraStatValue): ?>
                        <span class="text-green-600">
                            <?php echo $svgIcons['statusTrue']; ?>

                        </span>
                        <?php else: ?>
                        <span class="text-red-600">
                            <?php echo $svgIcons['statusFalse']; ?>

                        </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php else: ?>
                        <?php echo e($extraStatValue); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>
    </div>
</div><?php /**PATH C:\laragon\www\Highblossom\vendor\lubusin\laravel-decomposer\src\views\components\extra_stats.blade.php ENDPATH**/ ?>