<div class="flex flex-col gap-8 lg:flex-row lg:gap-12">
    
    <div class="min-w-0 flex-1">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 1; $i <= 6; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="animate-pulse overflow-hidden rounded-2xl border border-white/10 bg-white/5">
                    
                    <div class="aspect-[16/10] bg-gradient-to-br from-white/10 to-white/5"></div>

                    
                    <div class="p-6">
                        
                        <div class="mb-4 flex items-center gap-3">
                            <div class="h-4 w-20 animate-pulse rounded bg-white/10"></div>
                            <div class="h-1 w-1 rounded-full bg-white/20"></div>
                            <div class="h-4 w-16 animate-pulse rounded bg-white/10"></div>
                        </div>

                        
                        <div class="mb-3 h-6 animate-pulse rounded bg-white/10"></div>
                        <div class="mb-4 h-6 w-4/5 animate-pulse rounded bg-white/10"></div>

                        
                        <div class="mb-4 space-y-2">
                            <div class="h-4 animate-pulse rounded bg-white/10"></div>
                            <div class="h-4 w-5/6 animate-pulse rounded bg-white/10"></div>
                            <div class="h-4 w-4/6 animate-pulse rounded bg-white/10"></div>
                        </div>

                        
                        <div class="mb-6 h-4 w-20 animate-pulse rounded bg-white/10"></div>

                        
                        <div class="flex items-center gap-3 border-t border-white/5 pt-4">
                            <div class="h-10 w-10 animate-pulse rounded-full bg-white/10"></div>
                            <div class="flex-1">
                                <div class="mb-1 h-4 w-24 animate-pulse rounded bg-white/10"></div>
                                <div class="h-3 w-12 animate-pulse rounded bg-white/10"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>

    
    <aside class="w-full flex-shrink-0 space-y-6 lg:w-80">
        
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
            <div class="mb-5 flex items-center gap-3">
                <div class="h-10 w-10 animate-pulse rounded-xl bg-gradient-to-br from-white/10 to-white/5"></div>
                <div class="h-6 w-24 animate-pulse rounded bg-white/10"></div>
            </div>
            <div class="space-y-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div
                        class="h-10 animate-pulse rounded-lg bg-white/10"
                        style="animation-delay: <?php echo e($i * 0.1); ?>s"
                    ></div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>

        
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
            <div class="mb-5 flex items-center gap-3">
                <div class="h-10 w-10 animate-pulse rounded-xl bg-gradient-to-br from-white/10 to-white/5"></div>
                <div class="h-6 w-16 animate-pulse rounded bg-white/10"></div>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 1; $i <= 8; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div
                        class="h-8 w-20 animate-pulse rounded-full bg-white/10"
                        style="animation-delay: <?php echo e($i * 0.05); ?>s"
                    ></div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </aside>
</div>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/livewire/blog-posts/placeholder.blade.php ENDPATH**/ ?>