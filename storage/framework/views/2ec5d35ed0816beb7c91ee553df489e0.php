<?php
    $routeName = $routeName ?? 'jobs-monitor.dashboard';

    $pageBtn = 'inline-flex items-center justify-center h-8 min-w-8 px-2.5 text-xs font-medium rounded-md border border-border bg-card text-foreground hover:bg-accent hover:text-accent-foreground transition-colors';
    $pageBtnActive = 'inline-flex items-center justify-center h-8 min-w-8 px-2.5 text-xs font-semibold rounded-md bg-primary text-primary-foreground shadow-xs';
    $pageBtnGhost = 'inline-flex items-center justify-center h-8 min-w-8 px-2.5 text-xs font-medium rounded-md text-muted-foreground hover:bg-accent hover:text-accent-foreground transition-colors';
?>
<div class="px-5 py-3.5 border-t border-border flex flex-wrap items-center justify-between gap-3">
    <div class="text-xs text-muted-foreground">
        Page <span class="font-medium text-foreground tabular-nums"><?php echo e($currentPage); ?></span>
        of <span class="font-medium text-foreground tabular-nums"><?php echo e($lastPage); ?></span>
    </div>
    <div class="flex items-center gap-1">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentPage > 1): ?>
            <a href="<?php echo e(route($routeName, array_merge($extraParams, [$pageParam => $currentPage - 1]))); ?>"
               class="<?php echo e($pageBtn); ?> gap-1">
                <i data-lucide="chevron-left" class="text-[13px]"></i>
                Prev
            </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php
            $start = max(1, $currentPage - 2);
            $end = min($lastPage, $currentPage + 2);
        ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($start > 1): ?>
            <a href="<?php echo e(route($routeName, array_merge($extraParams, [$pageParam => 1]))); ?>" class="<?php echo e($pageBtn); ?>">1</a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($start > 2): ?><span class="<?php echo e($pageBtnGhost); ?> pointer-events-none">…</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($p = $start; $p <= $end; $p++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <a href="<?php echo e(route($routeName, array_merge($extraParams, [$pageParam => $p]))); ?>"
               class="<?php echo e($p === $currentPage ? $pageBtnActive : $pageBtn); ?> tabular-nums"><?php echo e($p); ?></a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($end < $lastPage): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($end < $lastPage - 1): ?><span class="<?php echo e($pageBtnGhost); ?> pointer-events-none">…</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <a href="<?php echo e(route($routeName, array_merge($extraParams, [$pageParam => $lastPage]))); ?>" class="<?php echo e($pageBtn); ?> tabular-nums"><?php echo e($lastPage); ?></a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentPage < $lastPage): ?>
            <a href="<?php echo e(route($routeName, array_merge($extraParams, [$pageParam => $currentPage + 1]))); ?>"
               class="<?php echo e($pageBtn); ?> gap-1">
                Next
                <i data-lucide="chevron-right" class="text-[13px]"></i>
            </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form method="GET" action="<?php echo e(route($routeName)); ?>" class="flex items-center gap-1 ml-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $extraParams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <input type="hidden" name="<?php echo e($k); ?>" value="<?php echo e($v); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <input type="number" name="<?php echo e($pageParam); ?>" min="1" max="<?php echo e($lastPage); ?>" placeholder="#"
                   class="h-8 w-14 rounded-md border border-input bg-card text-xs text-foreground px-2 tabular-nums focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring">
            <button type="submit" class="<?php echo e($pageBtn); ?>">Go</button>
        </form>
    </div>
</div>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\partials\pagination.blade.php ENDPATH**/ ?>