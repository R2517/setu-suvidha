{{-- Alert block: Important notices --}}
@php
    $variant = $block['variant'] ?? 'warning';
    $icons = [
        'info' => 'info',
        'success' => 'check-circle',
        'warning' => 'alert-triangle',
        'danger' => 'alert-octagon',
    ];
    $colors = [
        'info' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-700 text-blue-900 dark:text-blue-100',
        'success' => 'bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-700 text-green-900 dark:text-green-100',
        'warning' => 'bg-amber-50 dark:bg-amber-900/20 border-amber-300 dark:border-amber-700 text-amber-900 dark:text-amber-100',
        'danger' => 'bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700 text-red-900 dark:text-red-100',
    ];
@endphp

<div class="my-6 p-5 rounded-xl border-l-4 {{ $colors[$variant] ?? $colors['warning'] }}">
    <div class="flex items-start gap-3">
        <i data-lucide="{{ $icons[$variant] ?? 'alert-triangle' }}" class="w-6 h-6 flex-shrink-0 mt-0.5 pointer-events-none"></i>
        <div class="flex-1">
            @if(!empty($block['title']))
            <h4 class="font-bold text-lg mb-2">{{ $block['title'] }}</h4>
            @if(!empty($block['title_mr']))
            <p class="font-semibold text-sm mb-3 opacity-90">{{ $block['title_mr'] }}</p>
            @endif
            @endif

            @if(!empty($block['content']))
            <div class="prose-sm">{{ $block['content'] }}</div>
            @endif
            
            @if(!empty($block['content_mr']))
            <div class="mt-2 text-sm opacity-80">{{ $block['content_mr'] }}</div>
            @endif
        </div>
    </div>
</div>
