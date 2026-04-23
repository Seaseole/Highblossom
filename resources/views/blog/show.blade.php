<x-layouts::site>
    <div class="min-h-screen bg-[#0A0A0F] pt-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <article>
                @if($post->featured_image_url)
                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-96 object-cover rounded-2xl mb-8">
                @endif

                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-[#FAFAFA] mb-4">{{ $post->title }}</h1>
                    
                    <div class="flex items-center gap-4 text-sm text-[#71717A] mb-4">
                        <span>{{ $post->published_at?->format('M d, Y') }}</span>
                        @if($post->categories->count() > 0)
                            <span>•</span>
                            @foreach($post->categories as $category)
                                <a href="{{ route('blog', ['category' => $category->slug]) }}" class="hover:text-[#DC2626]">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>

                    @if($post->tags->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                                <a href="{{ route('blog', ['tag' => $tag->slug]) }}" class="text-xs bg-white/5 border border-white/10 px-3 py-1 rounded-full text-[#A1A1AA] hover:border-[#DC2626]/30 transition-colors">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="prose prose-invert max-w-none">
                    @if($post->content && is_array($post->content))
                        @foreach($post->content as $block)
                            @if($block['type'] === 'paragraph')
                                @cbParagraph($block['attributes'])
                            @elseif($block['type'] === 'heading')
                                @cbHeading($block['attributes'])
                            @elseif($block['type'] === 'image')
                                @cbImage($block['attributes'])
                            @elseif($block['type'] === 'quote')
                                @cbQuote($block['attributes'])
                            @elseif($block['type'] === 'code')
                                @cbCode($block['attributes'])
                            @elseif($block['type'] === 'list')
                                @cbList($block['attributes'])
                            @elseif($block['type'] === 'cta')
                                @cbCta($block['attributes'])
                            @elseif($block['type'] === 'video')
                                @cbVideo($block['attributes'])
                            @endif
                        @endforeach
                    @endif
                </div>
            </article>

            @if($relatedPosts->count() > 0)
                <div class="mt-16">
                    <h2 class="text-2xl font-bold text-[#FAFAFA] mb-6">Related Posts</h2>
                    <div class="grid gap-6 md:grid-cols-3">
                        @foreach($relatedPosts as $relatedPost)
                            <article class="bg-white/5 border border-white/10 rounded-xl p-4 hover:border-white/20 transition-colors">
                                @if($relatedPost->featured_image_url)
                                    <img src="{{ $relatedPost->featured_image_url }}" alt="{{ $relatedPost->title }}" class="w-full h-32 object-cover rounded-lg mb-3">
                                @endif
                                
                                <h3 class="text-lg font-semibold text-[#FAFAFA] mb-2">
                                    <a href="{{ route('blog.show', $relatedPost->slug) }}" class="hover:text-[#DC2626] transition-colors">
                                        {{ $relatedPost->title }}
                                    </a>
                                </h3>
                                
                                <p class="text-sm text-[#71717A]">{{ $relatedPost->published_at?->format('M d, Y') }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-8">
                <a href="{{ route('blog') }}" class="inline-flex items-center text-[#A1A1AA] hover:text-[#DC2626] transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Blog
                </a>
            </div>
        </div>
    </div>
</x-layouts::site>
