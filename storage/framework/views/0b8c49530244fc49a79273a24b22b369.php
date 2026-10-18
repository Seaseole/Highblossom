<?php if (isset($component)) { $__componentOriginal52b6740a4059545a9135423805a466b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52b6740a4059545a9135423805a466b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::site','data' => ['title' => 'Gallery']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::site'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Gallery']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <!-- Hero Section -->
    <section class="relative bg-[#0A0A0F] pt-32 pb-20">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <div class="mb-4 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    <?php echo e(__('gallery.label')); ?>

                </div>
                <h1 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                    <?php echo e(__('gallery.title')); ?>

                </h1>
                <p class="text-lg leading-relaxed text-[#A1A1AA]"><?php echo e(__('gallery.description')); ?></p>
            </div>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section class="bg-[#0A0A0F] py-24">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <!-- Category Filters -->
            <div class="mb-16 flex flex-wrap justify-center gap-3">
                <a
                    href="<?php echo e(route('gallery')); ?>"
                    wire:navigate
                    class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all <?php echo e(!$category ? 'bg-[#DC2626] text-white' : 'glass-card text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/[0.06]'); ?>"
                >
                    <?php echo e(__('gallery.all_projects')); ?>

                </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <a
                        href="<?php echo e(route('gallery', ['category' => $cat->slug])); ?>"
                        wire:navigate
                        class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all <?php echo e($category === $cat->slug ? 'bg-[#DC2626] text-white' : 'glass-card text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/[0.06]'); ?>"
                    >
                        <?php echo e($cat->name); ?>

                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <!-- Masonry Grid -->
            <div class="grid auto-rows-[300px] grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $isFeatured = $image->is_featured || $index === 0;
                        $rowSpan = $isFeatured ? 'md:row-span-2' : '';
                    ?>
                    <div class="group relative overflow-hidden rounded-2xl cursor-pointer <?php echo e($rowSpan); ?> <?php echo e($index % 4 === 0 ? 'lg:row-span-2' : ''); ?>">
                        <img
                            alt="<?php echo e($image->title); ?>"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            src="<?php echo e($image->image_url); ?>"
                            loading="lazy"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F]/90 via-[#0A0A0F]/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <div class="absolute right-0 bottom-0 left-0 translate-y-4 p-6 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
                            <span class="text-xs font-semibold tracking-wider text-[#DC2626] uppercase"><?php echo e($image->category->name ?? '-'); ?></span>
                            <h3 class="font-headline mt-1 text-xl font-bold text-[#FAFAFA]"><?php echo e($image->title); ?></h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($image->description): ?>
                                <p class="mt-2 line-clamp-2 text-sm text-[#A1A1AA]"><?php echo e($image->description); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="mt-4 flex items-center gap-3">
                                <button
                                    onclick="openLightbox(<?php echo e($index); ?>)"
                                    class="rounded-full bg-[#DC2626] px-4 py-2 text-xs font-bold text-white transition-colors hover:bg-[#B91C1C]"
                                >
                                    <?php echo e(__('gallery.quick_view')); ?>

                                </button>
                                <a
                                    href="<?php echo e(route('gallery.show', $image)); ?>"
                                    wire:navigate
                                    class="rounded-full bg-white/10 px-4 py-2 text-xs font-bold text-[#FAFAFA] backdrop-blur-md transition-colors hover:bg-white/20"
                                >
                                    <?php echo e(__('gallery.project_details')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <div class="col-span-1 flex flex-col items-center justify-center pt-16 pb-20 text-center md:col-span-2 md:pt-32 lg:col-span-3">
                        <h3 class="font-headline mb-2 text-2xl font-bold text-[#FAFAFA]">
                            <?php echo e(__('gallery.no_projects')); ?>

                        </h3>
                        <p class="mb-8 max-w-md text-[#A1A1AA]">
                            <?php echo e(__('gallery.no_projects_description')); ?>

                        </p>
                        <?php if (isset($component)) { $__componentOriginal1346536acca2f562295db5577845269d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1346536acca2f562295db5577845269d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.contact-buttons','data' => ['size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.contact-buttons'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'lg']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1346536acca2f562295db5577845269d)): ?>
<?php $attributes = $__attributesOriginal1346536acca2f562295db5577845269d; ?>
<?php unset($__attributesOriginal1346536acca2f562295db5577845269d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1346536acca2f562295db5577845269d)): ?>
<?php $component = $__componentOriginal1346536acca2f562295db5577845269d; ?>
<?php unset($__componentOriginal1346536acca2f562295db5577845269d); ?>
<?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Load More -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($images->hasMorePages()): ?>
                <div class="mt-16 text-center">
                    <a
                        href="<?php echo e(route('gallery', array_merge(request()->all(), ['page' => $images->currentPage() + 1]))); ?>"
                        wire:navigate
                        class="btn-glass px-8 py-4 text-lg"
                    >
                        <span><?php echo e(__('gallery.load_more')); ?></span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    <!-- Stats Section -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($galleryMetrics)): ?>
        <section class="border-t border-white/5 bg-gradient-to-b from-[#0A0A0F] to-[#121218] py-24">
            <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
                <div class="mb-12 text-center">
                    <div class="mb-4 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                        <?php echo e(__('gallery.performance_metrics')); ?>

                    </div>
                </div>
                <div class="grid grid-cols-1 <?php echo e(count($galleryMetrics) >= 3 ? 'md:grid-cols-3' : (count($galleryMetrics) === 2 ? 'md:grid-cols-2' : '')); ?> gap-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $galleryMetrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="glass-card rounded-2xl p-8 text-center">
                            <div class="mb-2 text-sm font-semibold tracking-wider text-[#A1A1AA] uppercase">
                                <?php echo e($metric['label']); ?>

                            </div>
                            <div class="font-headline text-5xl font-bold text-[#FAFAFA]">
                                <?php echo e($metric['value']); ?><span class="text-[#DC2626]"><?php echo e($metric['suffix']); ?></span>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Lightbox -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($images->count() > 0): ?>
        <div id="lightbox" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-[#0A0A0F]/95 backdrop-blur-xl" onclick="closeLightbox()"></div>
        <button
            onclick="closeLightbox()"
            class="glass-card absolute top-6 right-6 z-10 flex h-12 w-12 items-center justify-center rounded-full transition-colors hover:bg-white/[0.1]"
        >
            <svg class="h-6 w-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <button
            onclick="prevImage()"
            class="glass-card absolute top-1/2 left-6 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full transition-colors hover:bg-white/[0.1]"
        >
            <svg class="h-6 w-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button
            onclick="nextImage()"
            class="glass-card absolute top-1/2 right-6 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full transition-colors hover:bg-white/[0.1]"
        >
            <svg class="h-6 w-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        <div class="relative flex h-full items-center justify-center p-20">
            <div class="flex max-w-6xl flex-col items-center gap-8 lg:flex-row">
                <img id="lightbox-img" src="" alt="" class="max-h-[70vh] max-w-full rounded-2xl object-contain" />
                <div id="lightbox-info" class="text-center lg:max-w-md lg:text-left"></div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php $__env->startPush('scripts'); ?>
    <script>
        <?php
            $galleryData = $images->map(fn ($img) => [
                'src' => $img->image_url,
                'title' => $img->title,
                'category' => $img->category->name ?? '-',
                'description' => $img->description,
                'location_address' => $img->location_address,
                'google_maps_url' => $img->google_maps_url,
            ])->toArray();
        ?>

        // The dataset is per page (category filter, load-more), so this has to re-run on
        // every view, wire:navigate included. Everything lives in a closure: a bare
        // top-level `const` threw a redeclaration SyntaxError the second time a visitor
        // reached the gallery, silently killing the lightbox.
        (function () {
            const galleryImages = <?php echo json_encode($galleryData, 15, 512) ?>;

            let currentImageIndex = 0;

            function openLightbox(index) {
                const lightbox = document.getElementById('lightbox');
                const img = document.getElementById('lightbox-img');
                const infoDiv = document.getElementById('lightbox-info');

                if (!lightbox || !img || !infoDiv) {
                    return;
                }

                currentImageIndex = index;

                if (index < 0 || index >= galleryImages.length) {
                    return;
                }

                img.src = galleryImages[index].src;
                img.alt = galleryImages[index].title;

                // Update info section
                let infoHtml =
                    '<div class="text-[#DC2626] text-xs font-semibold uppercase tracking-wider">' +
                    galleryImages[index].category +
                    '</div>';
                infoHtml +=
                    '<h3 class="text-[#FAFAFA] text-2xl font-bold font-headline mt-1">' + galleryImages[index].title + '</h3>';

                if (galleryImages[index].description) {
                    infoHtml += '<p class="text-[#A1A1AA] mt-2">' + galleryImages[index].description + '</p>';
                }

                if (galleryImages[index].location_address && galleryImages[index].google_maps_url) {
                    infoHtml += '<div class="mt-4 flex items-center">';
                    infoHtml +=
                        '<svg class="w-5 h-5 text-[#DC2626] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                    infoHtml +=
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>';
                    infoHtml +=
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>';
                    infoHtml += '</svg>';
                    infoHtml +=
                        '<a href="' +
                        galleryImages[index].google_maps_url +
                        '" target="_blank" rel="noopener noreferrer" class="text-[#FAFAFA] hover:text-[#DC2626] transition-colors">';
                    infoHtml += galleryImages[index].location_address;
                    infoHtml += '</a>';
                    infoHtml += '</div>';
                }

                infoDiv.innerHTML = infoHtml;

                lightbox.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeLightbox() {
                const lightbox = document.getElementById('lightbox');

                if (lightbox) {
                    lightbox.classList.add('hidden');
                }

                document.body.style.overflow = '';
            }

            function nextImage() {
                if (galleryImages.length === 0) {
                    return;
                }

                currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
                openLightbox(currentImageIndex);
            }

            function prevImage() {
                if (galleryImages.length === 0) {
                    return;
                }

                currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
                openLightbox(currentImageIndex);
            }

            // The inline onclick handlers in the markup resolve these by name, so each
            // view republishes them against its own dataset.
            window.openLightbox = openLightbox;
            window.closeLightbox = closeLightbox;
            window.nextImage = nextImage;
            window.prevImage = prevImage;
        })();

        // Bound once per document, and a no-op anywhere the lightbox is not actually open,
        // so Escape and the arrow keys stop throwing on the rest of the site.
        if (! window.galleryLightboxKeysBound) {
            window.galleryLightboxKeysBound = true;

            document.addEventListener('keydown', function (e) {
                const lightbox = document.getElementById('lightbox');

                if (!lightbox || lightbox.classList.contains('hidden')) {
                    return;
                }

                if (e.key === 'Escape') window.closeLightbox();
                if (e.key === 'ArrowRight') window.nextImage();
                if (e.key === 'ArrowLeft') window.prevImage();
            });
        }
    </script>
    <?php $__env->stopPush(); ?>
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views/site/gallery.blade.php ENDPATH**/ ?>