<div class="w-full">
    <div class="w-full">
        <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-md shadow-sm" x-data="reportComponent()" x-init="$nextTick(() => init())">
            <p class="mb-4 font-normal">Please share this information for troubleshooting:</p>
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <button id="btn-report" @click="showReport = !showReport" class="bg-gray-700 text-white text-sm px-4 py-2 rounded cursor-pointer hover:bg-blue-500">
                    Get System Report
                </button>
                <a href="https://github.com/lubusIN/laravel-decomposer/blob/master/report.md" target="_blank" id="btn-about-report"
                    class="bg-white border-black hover:bg-white text-black text-sm px-4 py-2 rounded border">
                    Understand Report
                </a>
            </div>

            <div id="report-wrapper" x-show="showReport" x-transition.duration.400ms.ease-in-out>
                <textarea name="txt-report" id="txt-report"
                    class="w-full border rounded p-2 text-sm font-mono bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 my-8"
                    rows="10" spellcheck="false" x-ref="reportText"
                    @focus="$refs.reportText.select()">
                        ### Laravel Environment

                        - Laravel Version: <?php echo e($laravelEnv['version']); ?>

                        - Timezone: <?php echo e($laravelEnv['timezone']); ?>

                        - Debug Mode: <?php echo $laravelEnv['debug_mode'] ? '&#10004;' : '&#10008;'; ?>

                        - Storage Dir Writable: <?php echo $laravelEnv['storage_dir_writable'] ? '&#10004;' : '&#10008;'; ?>

                        - Cache Dir Writable: <?php echo $laravelEnv['cache_dir_writable'] ? '&#10004;' : '&#10008;'; ?>

                        - Decomposer Version: <?php echo e($laravelEnv['decomposer_version']); ?>

                        - App Size: <?php echo e($laravelEnv['app_size']); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $laravelExtras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extraStatKey => $extraStatValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        - <?php echo e($extraStatKey); ?>: <?php echo e(is_bool($extraStatValue) ? ($extraStatValue ? '&#10004;' : '&#10008;') : $extraStatValue); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                        ### Server Environment

                        - PHP Version: <?php echo e($serverEnv['version']); ?>

                        - Server Software: <?php echo e($serverEnv['server_software']); ?>

                        - Server OS: <?php echo e($serverEnv['server_os']); ?>

                        - Database: <?php echo e($serverEnv['database_connection_name']); ?>

                        - SSL Installed: <?php echo $serverEnv['ssl_installed'] ? '&#10004;' : '&#10008;'; ?>

                        - Cache Driver: <?php echo e($serverEnv['cache_driver']); ?>

                        - Session Driver: <?php echo e($serverEnv['session_driver']); ?>

                        - Openssl Ext: <?php echo $serverEnv['openssl'] ? '&#10004;' : '&#10008;'; ?>

                        - PDO Ext: <?php echo $serverEnv['pdo'] ? '&#10004;' : '&#10008;'; ?>

                        - Mbstring Ext: <?php echo $serverEnv['mbstring'] ? '&#10004;' : '&#10008;'; ?>

                        - Tokenizer Ext: <?php echo $serverEnv['tokenizer']  ? '&#10004;' : '&#10008;'; ?>

                        - XML Ext: <?php echo $serverEnv['xml'] ? '&#10004;' : '&#10008;'; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $serverExtras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extraStatKey => $extraStatValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        - <?php echo e($extraStatKey); ?>: <?php echo e(is_bool($extraStatValue) ? ($extraStatValue ? '&#10004;' : '&#10008;') : $extraStatValue); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                        ### Installed Packages &amp; their version numbers

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        - <?php echo e($package['name']); ?> : <?php echo e($package['version']); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($extraStats)): ?>
                        ### Extra Information

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $extraStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extraStatKey => $extraStatValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        - <?php echo e($extraStatKey); ?> : <?php echo e(is_bool($extraStatValue) ? ($extraStatValue ? '&#10004;' : '&#10008;')
                            
                            : $extraStatValue); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </textarea>

                <button id="copy-report" type="button" @click="copyReport" class="mt-4 bg-black hover:bg-red-500 text-white text-sm px-4 py-2 rounded cursor-pointer">
                    Copy Report
                </button>
                <span x-show="copied" x-transition class="ml-2 text-gray-500 font-medium">
                    Copied!
                </span>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\Highblossom\vendor\lubusin\laravel-decomposer\src\views\components\report_component.blade.php ENDPATH**/ ?>