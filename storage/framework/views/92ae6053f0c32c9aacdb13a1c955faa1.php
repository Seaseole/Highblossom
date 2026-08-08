<div class="bg-white rounded-md border border-gray-200 mt-6">
    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex flex-wrap items-center gap-2">
        <span class="text-gray-500"> <?php echo $svgIcons['serverIcon']; ?> </span>
        <h3 class="text-lg text-gray-700 font-normal">
            Server Environment
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left divide-y divide-gray-200">
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-4 py-2 font-normal w-1/3">PHP Version</td>
                    <td class="px-4 py-2 text-gray-500 text-right"><?php echo e($serverEnv['version']); ?></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-normal align-top">Server Software</td>
                    <td class="px-4 py-2 text-gray-500 text-right"><?php echo e($serverEnv['server_software']); ?></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-normal align-top">Server OS</td>
                    <td class="px-4 py-2 text-gray-500 text-right"><?php echo e($serverEnv['server_os']); ?></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-normal">Database</td>
                    <td class="px-4 py-2 text-gray-500 text-right"><?php echo e($serverEnv['database_connection_name']); ?></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-normal">SSL Installed</td>
                    <td class="px-4 py-2 float-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($serverEnv['ssl_installed']): ?>
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
                    <td class="px-4 py-2 font-normal">Cache Driver</td>
                    <td class="px-4 py-2 text-gray-500 text-right"><?php echo e($serverEnv['cache_driver']); ?></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-normal">Session Driver</td>
                    <td class="px-4 py-2 text-gray-500 text-right"><?php echo e($serverEnv['session_driver']); ?></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-normal">Openssl Ext</td>
                    <td class="px-4 py-2 float-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($serverEnv['openssl']): ?>
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
                    <td class="px-4 py-2 font-normal">PDO Ext</td>
                    <td class="px-4 py-2 float-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($serverEnv['pdo']): ?>
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
                    <td class="px-4 py-2 font-normal">Mbstring Ext</td>
                    <td class="px-4 py-2 float-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($serverEnv['mbstring']): ?>
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
                    <td class="px-4 py-2 font-normal">Tokenizer Ext</td>
                    <td class="px-4 py-2 float-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($serverEnv['tokenizer']): ?>
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
                    <td class="px-4 py-2 font-normal">XML Ext</td>
                    <td class="px-4 py-2 float-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($serverEnv['xml']): ?>
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $serverExtras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extraStatKey => $extraStatValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
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
</div><?php /**PATH C:\laragon\www\Highblossom\vendor\lubusin\laravel-decomposer\src\views\components\server_env.blade.php ENDPATH**/ ?>