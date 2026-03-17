{{-- Heading block: H2/H3 section headings --}}
@php
    $level = $block['level'] ?? 2;
    $text = $block['text'] ?? '';
    $textMr = $block['text_mr'] ?? '';
    $id = $block['id'] ?? Str::slug($text);
    $tag = 'h' . $level;
@endphp

<{{ $tag }} id="{{ $id }}" class="font-black text-gray-900 dark:text-white mt-8 mb-4 {{ $level == 2 ? 'text-3xl' : 'text-2xl' }}">
    {{ $text }}
</{{ $tag }}>

@if($textMr)
<p class="text-lg text-gray-600 dark:text-gray-400 font-semibold -mt-2 mb-4">{{ $textMr }}</p>
@endif
