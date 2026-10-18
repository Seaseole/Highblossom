<?php if (isset($component)) { $__componentOriginal52b6740a4059545a9135423805a466b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52b6740a4059545a9135423805a466b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::site','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::site'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <section class="min-h-screen bg-[#0A0A0F] pt-20">
        <div class="mx-auto max-w-[1400px] px-6 py-16 lg:px-8 lg:py-24">
            <span class="inline-flex items-center rounded-full border border-[#DC2626]/20 bg-[#DC2626]/10 px-3 py-1 text-xs font-medium tracking-wider text-[#DC2626] uppercase">
                Read our blog
            </span>

            <h1 class="font-headline mt-5 text-4xl leading-[1.08] font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                Our Blog
            </h1>

            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-[#A1A1AA]">
                Insights and resources from the work behind
                <span class="font-semibold text-[#FAFAFA]"><?php echo e(config('app.name')); ?></span>.
            </p>

            <div class="mt-12 lg:mt-16">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('blog-posts', ['search' => $search,'category-slug' => $categorySlug,'tag-slug' => $tagSlug,'lazy' => true]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-495155189-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
            </div>
        </div>
    </section>

    <?php if (isset($component)) { $__componentOriginal51f8a69de7da716a8862293e34502737 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal51f8a69de7da716a8862293e34502737 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.blog.search-palette','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('blog.search-palette'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal51f8a69de7da716a8862293e34502737)): ?>
<?php $attributes = $__attributesOriginal51f8a69de7da716a8862293e34502737; ?>
<?php unset($__attributesOriginal51f8a69de7da716a8862293e34502737); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal51f8a69de7da716a8862293e34502737)): ?>
<?php $component = $__componentOriginal51f8a69de7da716a8862293e34502737; ?>
<?php unset($__componentOriginal51f8a69de7da716a8862293e34502737); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal52b6740a4059545a9135423805a466b9)): ?>
<?php $attributes = $__attributesOriginal52b6740a4059545a9135423805a466b9; ?>
<?php unset($__attributesOriginal52b6740a4059545a9135423805a466b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal52b6740a4059545a9135423805a466b9)): ?>
<?php $component = $__componentOriginal52b6740a4059545a9135423805a466b9; ?>
<?php unset($__componentOriginal52b6740a4059545a9135423805a466b9); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/blog/index.blade.php ENDPATH**/ ?>