<div class="space-y-12">
    
    <div class="animate-pulse">
        <div class="relative h-[26rem] w-full overflow-hidden rounded-3xl border border-white/10 bg-white/5 sm:h-[30rem]">
            <div class="absolute inset-0 bg-gradient-to-t from-white/10 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 space-y-4 p-6 sm:p-10">
                <div class="h-9 w-2/3 rounded bg-white/10"></div>
                <div class="h-4 w-1/2 rounded bg-white/10"></div>
                <div class="h-4 w-40 rounded bg-white/10"></div>
            </div>
        </div>
    </div>

    
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-white/10 pb-4">
        <div class="flex animate-pulse gap-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 1; $i <= 4; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="h-4 w-20 rounded bg-white/10"></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <div class="h-10 w-72 animate-pulse rounded-full bg-white/10"></div>
    </div>

    
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 1; $i <= 6; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="animate-pulse overflow-hidden rounded-3xl border border-white/10 bg-white/5">
                <div class="aspect-[16/10] bg-gradient-to-br from-white/10 to-white/5"></div>
                <div class="space-y-3 p-6">
                    <div class="h-3 w-24 rounded bg-white/10"></div>
                    <div class="h-6 w-5/6 rounded bg-white/10"></div>
                    <div class="h-4 w-full rounded bg-white/10"></div>
                    <div class="h-4 w-4/6 rounded bg-white/10"></div>
                    <div class="flex items-center gap-3 border-t border-white/5 pt-4">
                        <div class="h-8 w-8 rounded-full bg-white/10"></div>
                        <div class="h-4 w-24 rounded bg-white/10"></div>
                    </div>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/livewire/blog-posts/placeholder.blade.php ENDPATH**/ ?>