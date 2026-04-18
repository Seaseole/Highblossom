{{ '<'.'?xml version="1.0" encoding="UTF-8" ?' }}'>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Blog Index -->
    <url>
        <loc>{{ route('blog.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- RSS Feed -->
    <url>
        <loc>{{ route('blog.rss') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.5</priority>
    </url>

    <!-- Posts -->
    @foreach($posts as $post)
        <url>
            <loc>{{ route('blog.show', $post) }}</loc>
            <lastmod>{{ $post->updated_at?->toIso8601String() ?? $post->published_at?->toIso8601String() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    <!-- Categories -->
    @foreach($categories as $category)
        <url>
            <loc>{{ route('blog.category', $category) }}</loc>
            <lastmod>{{ $category->updated_at?->toIso8601String() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach

    <!-- Tags -->
    @foreach($tags as $tag)
        <url>
            <loc>{{ route('blog.tag', $tag) }}</loc>
            <lastmod>{{ $tag->updated_at?->toIso8601String() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.5</priority>
        </url>
    @endforeach
</urlset>
