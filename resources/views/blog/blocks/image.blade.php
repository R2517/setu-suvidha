{{-- Image block: Standalone image with caption --}}
<figure class="my-8">
    <img 
        src="{{ asset($block['src'] ?? '') }}" 
        alt="{{ $block['alt'] ?? '' }}" 
        @if(!empty($block['width'])) width="{{ $block['width'] }}" @endif
        @if(!empty($block['height'])) height="{{ $block['height'] }}" @endif
        class="rounded-xl w-full max-w-3xl mx-auto"
        loading="lazy"
    >
    @if(!empty($block['caption']))
    <figcaption class="text-center text-sm text-gray-600 dark:text-gray-400 mt-3">
        {{ $block['caption'] }}
    </figcaption>
    @endif
    @if(!empty($block['caption_mr']))
    <figcaption class="text-center text-xs text-gray-500 dark:text-gray-500 mt-1">
        {{ $block['caption_mr'] }}
    </figcaption>
    @endif
</figure>
