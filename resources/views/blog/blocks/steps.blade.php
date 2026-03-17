{{-- Steps block: Step-by-step process (generates HowTo schema) --}}
<div class="my-8">
    @if(!empty($block['title']))
    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6">{{ $block['title'] }}</h3>
    @if(!empty($block['title_mr']))
    <p class="text-lg text-gray-600 dark:text-gray-400 font-semibold -mt-4 mb-6">{{ $block['title_mr'] }}</p>
    @endif
    @endif

    <div class="space-y-6">
        @foreach($block['items'] ?? [] as $index => $step)
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold">
                    {{ $index + 1 }}
                </div>
            </div>
            <div class="flex-1">
                @if(!empty($step['title']))
                <h4 class="font-bold text-lg text-gray-900 dark:text-white mb-2">{{ $step['title'] }}</h4>
                @if(!empty($step['title_mr']))
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ $step['title_mr'] }}</p>
                @endif
                @endif

                @if(!empty($step['description']))
                <p class="text-gray-700 dark:text-gray-300">{{ $step['description'] }}</p>
                @endif

                @if(!empty($step['description_mr']))
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $step['description_mr'] }}</p>
                @endif

                @if(!empty($step['image']))
                <img src="{{ asset($step['image']) }}" alt="{{ $step['image_alt'] ?? $step['title'] }}" class="mt-3 rounded-lg max-w-md" loading="lazy">
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

@if(!empty($block['generate_schema']) && $block['generate_schema'])
@php
$howToSteps = [];
foreach ($block['items'] ?? [] as $i => $step) {
    $s = ['@type' => 'HowToStep', 'position' => $i + 1, 'name' => $step['title'] ?? '', 'text' => $step['description'] ?? ''];
    if (!empty($step['image'])) $s['image'] = asset($step['image']);
    $howToSteps[] = $s;
}
$howToSchema = ['@context' => 'https://schema.org', '@type' => 'HowTo', 'name' => $block['title'] ?? '', 'step' => $howToSteps];
@endphp
<script type="application/ld+json">{!! json_encode($howToSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
