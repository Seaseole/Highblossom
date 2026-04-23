<x-layouts::site>
    <div class="min-h-screen bg-[#0A0A0F] pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-[#FAFAFA] mb-4">Blog</h1>
                <p class="text-[#A1A1AA]">Latest news, updates, and insights</p>
            </div>

            <div class="mb-8">
                <form method="GET" action="{{ route('blog') }}" class="flex gap-4">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}"
                        placeholder="Search posts..."
                        class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#FAFAFA] placeholder-[#71717A] focus:ring-2 focus:ring-[#DC2626] focus:border-transparent"
                    >
                    <button type="submit" class="bg-[#DC2626] hover:bg-[#B91C1C] text-white px-6 py-3 rounded-xl transition-colors">
                        Search
                    </button>
                </form>
            </div>

            <div class="flex gap-8">
                <div class="flex-1">
                    @if($posts->count() > 0)
                        <div class="grid gap-6">
                            @foreach($posts as $post)
                                <article class="bg-white/5 border border-white/10 rounded-xl p-6 hover:border-white/20 transition-colors">
                                    @if($post->featured_image_url)
                                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-48 object-cover rounded-lg mb-4">
                                    @endif
                                    
                                    <h2 class="text-2xl font-bold text-[#FAFAFA] mb-2">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-[#DC2626] transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h2>
                                    
                                    @if($post->excerpt)
                                        <p class="text-[#A1A1AA] mb-4">{{ $post->excerpt }}</p>
                                    @endif
                                    
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
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-[#A1A1AA]">No posts found.</p>
                        </div>
                    @endif
                </div>

                <aside class="w-64 hidden lg:block">
                    <div class="bg-white/5 border border-white/10 rounded-xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-[#FAFAFA] mb-4">Categories</h3>
                        <ul class="space-y-2">
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('blog', ['category' => $category->slug]) }}" class="text-[#A1A1AA] hover:text-[#DC2626] transition-colors {{ $categorySlug === $category->slug ? 'text-[#DC2626]' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-[#FAFAFA] mb-4">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                                <a href="{{ route('blog', ['tag' => $tag->slug]) }}" class="text-xs bg-white/5 border border-white/10 px-3 py-1 rounded-full text-[#A1A1AA] hover:border-[#DC2626]/30 transition-colors {{ $tagSlug === $tag->slug ? 'border-[#DC2626]/30' : '' }}">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-layouts::site>
