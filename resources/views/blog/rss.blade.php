{{ '<'.'?xml version="1.0" encoding="UTF-8" ?' }}'>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/">

    <channel>
        <title>{{ $companyName ?? 'Highblossom' }} Blog</title>
        <atom:link href="{{ route('blog.rss') }}" rel="self" type="application/rss+xml" />
        <link>{{ route('blog.index') }}</link>
        <description>Latest articles and updates from {{ $companyName ?? 'Highblossom' }}</description>
        <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
        <language>en</language>
        <sy:updatePeriod>daily</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>

        @foreach($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ route('blog.show', $post) }}</link>
                <guid isPermaLink="true">{{ route('blog.show', $post) }}</guid>
                <pubDate>{{ $post->published_at?->toRfc2822String() }}</pubDate>
                <dc:creator>{{ $post->author?->name ?? 'Team' }}</dc:creator>

                @foreach($post->tags as $tag)
                    <category>{{ $tag->name }}</category>
                @endforeach

                <description><![CDATA[{{ $post->excerpt }}]]></description>

                @if($post->featured_image)
                    <enclosure url="{{ $post->featured_image }}" type="image/jpeg" />
                @endif
            </item>
        @endforeach
    </channel>
</rss>
