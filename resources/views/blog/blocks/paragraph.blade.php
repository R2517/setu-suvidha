{{-- Paragraph block: Rich text content --}}
<div class="my-4">
    @if(!empty($block['content']))
        {!! strip_tags($block['content'], '<p><strong><em><a><br><ul><ol><li><blockquote><code>') !!}
    @endif
    
    @if(!empty($block['content_mr']))
        <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm italic">
            {!! strip_tags($block['content_mr'], '<p><strong><em><a><br><ul><ol><li><blockquote><code>') !!}
        </div>
    @endif
</div>
