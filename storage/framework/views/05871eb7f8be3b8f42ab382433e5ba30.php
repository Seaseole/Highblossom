<div class="bg-white rounded-md border border-gray-200">
    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex flex-wrap items-center gap-2">
        <span class="text-gray-500"> <?php echo $svgIcons['laravelIcon']; ?></span>
        <h3 class="text-lg text-gray-700 font-normal">
            Laravel Environment
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left divide-y divide-gray-200">
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-4 py-2 font-normal w-75">Laravel Version</td>
                    <td class="px-4 py-2 text-gray-500 text-right"><?php echo e($laravelEnv['version']); ?></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-normal">Timezone</td>
                    <td class="px-4 py-2 text-gray-500 text-right"><?php echo e($laravelEnv['timezone']); ?></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-normal">Debug Mode</td>
                    <td class="px-4 py-2 float-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($laravelEnv['debug_mode']): ?>
                        <span class="text-green-600">
                            <?php echo $svgIcons['statusTrue']; ?>

                        </span>
                        <?php else: ?>
                        <span class="text-red-600">
                            <?php echo $svgIcons['statusFalse']; ?>

                        </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-normal">Storage Dir Writable</td>
                    <td class="px-4 py-2 float-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($laravelEnv['storage_dir_writable']): ?>
                        <span class="text-green-600">
                            <?php echo $svgIcons['statusTrue']; ?>

                        </span>
                        <?php else: ?>
                        <span class="text-red-600">
                            <?php echo $svgIcons['statusFalse']; ?>

                        </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-normal">Cache Dir Writable</td>
                    <td class="px-4 py-2 float-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($laravelEnv['cache_dir_writable']): ?>
                        <span class="text-green-600">
                            <?php echo $svgIcons['statusTrue']; ?>

                        </span>
                        <?php else: ?>
                        <span class="text-red-600">
                            <?php echo $svgIcons['statusFalse']; ?>

                        </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-normal">Decomposer Version</td>
                    <td class="px-4 py-2 text-gray-500 text-right"><?php echo e($laravelEnv['decomposer_version']); ?></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-normal">App Size</td>
                    <td class="px-4 py-2 text-gray-500 text-right"><?php echo e($laravelEnv['app_size']); ?></td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $laravelExtras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extraStatKey => $extraStatValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td class="px-4 py-2 font-normal"><?php echo e($extraStatKey); ?></td>
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
</div><?php /**PATH C:\laragon\www\Highblossom\vendor\lubusin\laravel-decomposer\src\views\components\laravel_env.blade.php ENDPATH**/ ?>