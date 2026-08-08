<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search): ?>
        <span class="inline-flex items-center gap-2 rounded-full border border-[#DC2626]/20 bg-[#DC2626]/10 px-3 py-1 text-sm text-[#DC2626]">
            Search: <?php echo e($search); ?>

            <button wire:click="clearSearch" class="transition-colors hover:text-white">×</button>
        </span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category): ?>
        <span class="inline-flex items-center gap-2 rounded-full border border-[#DC2626]/20 bg-[#DC2626]/10 px-3 py-1 text-sm text-[#DC2626]">
            Category: <?php echo e($category->name); ?>

            <button wire:click="clearCategory" class="transition-colors hover:text-white">×</button>
        </span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tag): ?>
        <span class="inline-flex items-center gap-2 rounded-full border border-[#DC2626]/20 bg-[#DC2626]/10 px-3 py-1 text-sm text-[#DC2626]">
            Tag: #<?php echo e($tag->name); ?>

            <button wire:click="clearTag" class="transition-colors hover:text-white">×</button>
        </span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search || $categorySlug || $tagSlug): ?>
        <button wire:click="clearAll" class="ml-2 text-sm text-[#A1A1AA] transition-colors hover:text-[#DC2626]">
            Clear all
        </button>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/livewire/blog-posts/active-filters.blade.php ENDPATH**/ ?>