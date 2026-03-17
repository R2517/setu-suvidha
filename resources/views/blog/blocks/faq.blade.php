{{-- FAQ block: FAQ accordion with schema --}}
<div class="my-8" x-data="{ openIndex: null }">
    @if(!empty($block['title']))
    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6">{{ $block['title'] }}</h3>
    @if(!empty($block['title_mr']))
    <p class="text-lg text-gray-600 dark:text-gray-400 font-semibold -mt-4 mb-6">{{ $block['title_mr'] }}</p>
    @endif
    @endif

    <div class="space-y-3">
        @foreach($block['items'] ?? [] as $index => $faq)
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <button 
                @click="openIndex = openIndex === {{ $index }} ? null : {{ $index }}"
                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/50 transition"
            >
                <div class="flex-1 pr-4">
                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $faq['question'] ?? '' }}</h4>
                    @if(!empty($faq['question_mr']))
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $faq['question_mr'] }}</p>
                    @endif
                </div>
                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 transition-transform pointer-events-none" :class="{ 'rotate-180': openIndex === {{ $index }} }"></i>
            </button>
            <div 
                x-show="openIndex === {{ $index }}"
                x-collapse
                class="px-6 pb-4 border-t border-gray-100 dark:border-gray-800"
            >
                <div class="pt-4">
                    <p class="text-gray-700 dark:text-gray-300">{{ $faq['answer'] ?? '' }}</p>
                    @if(!empty($faq['answer_mr']))
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $faq['answer_mr'] }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@if(!empty($block['generate_schema']) && $block['generate_schema'])
@php
$faqEntities = [];
foreach ($block['items'] ?? [] as $faq) {
    $faqEntities[] = [
        '@type' => 'Question',
        'name' => $faq['question'] ?? '',
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer'] ?? ''],
    ];
}
$faqSchema = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faqEntities];
@endphp
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
