@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>SETU Suvidha Blog</title>
        <link>{{ url('/blog') }}</link>
        <description>Government schemes, guides, and updates for Maharashtra VLEs</description>
        <language>en</language>
        <atom:link href="{{ route('blog.feed') }}" rel="self" type="application/rss+xml" />
        <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
        
        @foreach($posts as $post)
        <item>
            <title>{{ $post->title }}</title>
            <link>{{ $post->url }}</link>
            <guid>{{ $post->url }}</guid>
            <pubDate>{{ $post->published_at->toRfc2822String() }}</pubDate>
            <description><![CDATA[{{ $post->excerpt ?? $post->getFirstParagraph() }}]]></description>
            @if($post->category)
            <category>{{ $post->category->name_en }}</category>
            @endif
            @if($post->featured_image)
            <enclosure url="{{ asset($post->featured_image) }}" type="image/jpeg" />
            @endif
        </item>
        @endforeach
    </channel>
</rss>
