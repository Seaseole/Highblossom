<div class="flex flex-col gap-8 lg:flex-row lg:gap-12">
    
    <div class="min-w-0 flex-1">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->posts->count() > 0): ?>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $wordCount = str_word_count(strip_tags($post->content['content'] ?? ''));
                        $readTime = max(1, ceil($wordCount / 200));
                        $author = $post->author ?? auth()->user();
                    ?>

                    <article
                        class="group overflow-hidden rounded-2xl border border-white/10 bg-white/5 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1 hover:border-[#DC2626]/30 hover:shadow-2xl hover:shadow-[#DC2626]/5"
                        style="animation: fadeUp 0.6s ease-out <?php echo e($index * 0.1); ?>s both;"
                        <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'post-'.e($post->id).''; ?>wire:key="post-<?php echo e($post->id); ?>"
                    >
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->featured_image_url): ?>
                            <div class="aspect-[16/10] overflow-hidden">
                                <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="relative block h-full">
                                    <img
                                        src="<?php echo e($post->featured_image_url); ?>"
                                        alt="<?php echo e($post->title); ?>"
                                        class="h-full w-full object-cover transition-transform duration-700 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-105"
                                        loading="lazy"
                                    />
                                </a>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <div class="p-6">
                            
                            <div class="mb-4 flex items-center gap-3">
                                <span class="text-sm text-[#71717A]"><?php echo e($post->published_at?->format('M d, Y')); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->categories->count() > 0): ?>
                                    <span class="h-1 w-1 rounded-full bg-white/20"></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $post->categories->take(1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <a
                                            href="<?php echo e(route('blog', ['category' => $category->slug])); ?>"
                                            class="text-sm font-medium text-[#DC2626] transition-colors hover:text-[#B91C1C]"
                                        >
                                            <?php echo e($category->name); ?>

                                        </a>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            
                            <h2 class="font-headline mb-3 line-clamp-2 text-xl font-bold text-[#FAFAFA] transition-colors duration-300 group-hover:text-[#DC2626]">
                                <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="block"> <?php echo e($post->title); ?> </a>
                            </h2>

                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->excerpt): ?>
                                <p class="mb-4 line-clamp-3 leading-relaxed text-[#A1A1AA]"><?php echo e($post->excerpt); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            
                            <a
                                href="<?php echo e(route('blog.show', $post->slug)); ?>"
                                class="mb-6 inline-flex items-center text-sm font-medium text-[#DC2626] transition-colors hover:text-[#B91C1C]"
                            >
                                Read More
                                <svg class="ml-1 h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>

                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($author): ?>
                                <div class="flex items-center gap-3 border-t border-white/5 pt-4">
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[#DC2626]/20">
                                        <span class="text-sm font-semibold text-[#DC2626]"><?php echo e($author->initials()); ?></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-[#FAFAFA]"><?php echo e($author->name); ?></p>
                                        <p class="text-xs text-[#71717A]">Author</p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </article>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            
            <div class="mt-12"><?php echo e($this->posts->links()); ?></div>
        <?php else: ?>
            
            <div class="py-20 text-center lg:py-32">
                <div class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-2xl border border-white/10 bg-white/5">
                    <svg class="h-10 w-10 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-semibold text-[#FAFAFA]">No posts found</h3>
                <p class="mx-auto mb-6 max-w-md text-[#A1A1AA]">
                    We couldn't find any articles matching your criteria. Try adjusting your search or filters.
                </p>
                
                
                
                
                
                
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <aside class="w-full flex-shrink-0 space-y-6 lg:w-80">
        
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#DC2626]/10">
                    <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-[#FAFAFA]">Categories</h3>
            </div>
            <ul class="space-y-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <li <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'category-'.e($category->id).''; ?>wire:key="category-<?php echo e($category->id); ?>">
                        <a
                            href="<?php echo e(route('blog', ['category' => $category->slug])); ?>"
                            class="flex items-center justify-between px-3 py-2 rounded-lg text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/5 transition-all duration-200 <?php echo e($this->categorySlug === $category->slug ? 'bg-[#DC2626]/10 text-[#DC2626] hover:bg-[#DC2626]/15' : ''); ?>"
                        >
                            <span><?php echo e($category->name); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->categorySlug === $category->slug): ?>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </a>
                    </li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
        
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#DC2626]/10">
                    <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-[#FAFAFA]">Tags</h3>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <a
                        href="<?php echo e(route('blog', ['tag' => $tag->slug])); ?>"
                        class="px-3 py-1.5 rounded-full text-sm border transition-all duration-200 <?php echo e($this->tagSlug === $tag->slug ? 'bg-[#DC2626]/10 border-[#DC2626]/30 text-[#DC2626]' : 'bg-white/5 border-white/10 text-[#A1A1AA] hover:border-[#DC2626]/30 hover:text-[#DC2626]'); ?>"
                        <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'tag-'.e($tag->id).''; ?>wire:key="tag-<?php echo e($tag->id); ?>"
                    >
                        #<?php echo e($tag->name); ?>

                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>

        
        <div class="relative overflow-hidden rounded-2xl border border-[#DC2626]/20 bg-gradient-to-br from-[#DC2626]/20 to-[#991B1B]/10 p-6">
            <div class="absolute top-0 right-0 h-32 w-32 translate-x-1/2 -translate-y-1/2 rounded-full bg-[#DC2626]/10 blur-3xl"></div>
            <div class="relative">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-[#DC2626]/20">
                    <svg class="h-6 w-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-[#FAFAFA]">Stay Updated</h3>
                <p class="mb-4 text-sm text-[#A1A1AA]">
                    Get the latest automotive tips and news delivered to your inbox.
                </p>
                <a href="<?php echo e(route('contact')); ?>" class="btn-premium w-full justify-center py-3 text-sm">
                    <span>Subscribe</span>
                </a>
            </div>
        </div>
    </aside>
</div>

<style>
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\livewire\blog-posts.blade.php ENDPATH**/ ?>