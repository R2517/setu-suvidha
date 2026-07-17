@extends('layouts.app')
@section('title', 'Ticket #' . $ticket->id . ' — SETU Suvidha')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('helpdesk.index') }}" class="text-gray-500 hover:text-gray-900 dark:hover:text-white flex items-center gap-1 font-medium transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Tickets
        </a>
        <span class="px-3 py-1 rounded-full text-sm font-semibold capitalize
            {{ $ticket->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
            {{ $ticket->status }}
        </span>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col h-[70vh]">
        {{-- Header --}}
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ $ticket->subject ?: 'Support Request #' . $ticket->id }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">Submitted on {{ $ticket->created_at->format('d M Y, h:i A') }}</p>
        </div>        {{-- Chat Area --}}
        <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 bg-slate-50 dark:bg-gray-950/50">
            {{-- Initial Message --}}
            <div class="flex items-start gap-3 md:gap-4 max-w-4xl">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-sm border border-blue-200 dark:border-blue-800/50">
                    <i data-lucide="user" class="w-4 h-4 md:w-5 md:h-5 text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="flex-1 bg-white dark:bg-gray-800 rounded-2xl rounded-tl-none px-4 py-3 md:px-5 md:py-4 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="text-[10px] font-bold text-gray-400 mb-1">You</div>
                    <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap text-sm leading-relaxed">{{ $ticket->message }}</p>
                    @if($ticket->attachment_path)
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline bg-blue-50 dark:bg-blue-900/30 px-3 py-1.5 rounded-lg">
                            <i data-lucide="paperclip" class="w-3.5 h-3.5"></i> View Attachment
                        </a>
                    </div>
                    @endif
                    <div class="text-[10px] text-gray-400 mt-2 text-right">
                        {{ $ticket->created_at->format('d M, h:i A') }}
                    </div>
                </div>
            </div>

            {{-- Replies --}}
            @foreach($ticket->messages as $msg)
            <div class="flex items-start gap-3 md:gap-4 max-w-4xl {{ $msg->sender_type === 'user' ? '' : 'flex-row-reverse ml-auto' }}">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center shrink-0 shadow-sm
                    {{ $msg->sender_type === 'admin' ? 'bg-amber-100 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/50' : 'bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/50' }}">
                    <i data-lucide="{{ $msg->sender_type === 'admin' ? 'shield' : 'user' }}" 
                       class="w-4 h-4 md:w-5 md:h-5 {{ $msg->sender_type === 'admin' ? 'text-amber-600 dark:text-amber-500' : 'text-blue-600 dark:text-blue-400' }}"></i>
                </div>
                <div class="flex-1 bg-white dark:bg-gray-800 rounded-2xl px-4 py-3 md:px-5 md:py-4 shadow-sm border border-gray-100 dark:border-gray-700
                    {{ $msg->sender_type === 'admin' ? 'rounded-tr-none bg-amber-50/30 dark:bg-amber-900/10' : 'rounded-tl-none' }}">
                    <div class="text-[10px] font-bold text-gray-400 mb-1">{{ $msg->sender_type === 'admin' ? 'Admin' : 'You' }}</div>
                    <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap text-sm leading-relaxed">{{ $msg->message }}</p>
                    @if($msg->attachment_path)
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ Storage::url($msg->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline bg-blue-50 dark:bg-blue-900/30 px-3 py-1.5 rounded-lg">
                            <i data-lucide="paperclip" class="w-3.5 h-3.5"></i> View Attachment
                        </a>
                    </div>
                    @endif
                    <div class="text-[10px] text-gray-400 mt-2 {{ $msg->sender_type === 'admin' ? 'text-left' : 'text-right' }}">
                        {{ $msg->created_at->format('d M, h:i A') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Reply Form --}}
        @if($ticket->status === 'open')
        <div class="p-4 border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 rounded-b-2xl">
            <form action="{{ route('helpdesk.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
                @csrf
                <textarea name="message" rows="3" placeholder="Type your reply to admin..." 
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500 resize-none dark:text-gray-200"></textarea>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <label class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition flex items-center gap-2 border border-gray-200">
                            <i data-lucide="paperclip" class="w-4 h-4"></i> Upload File/Photo
                            <input type="file" name="attachment" class="hidden" onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : ''">
                        </label>
                        <span id="file-name" class="text-xs text-gray-500 font-medium"></span>
                    </div>
                    
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-md transition flex items-center gap-2">
                        Send Reply <i data-lucide="send" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>
        </div>
        @else
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-center text-sm text-gray-500">
            This ticket is closed. If you need further assistance, please create a new ticket.
        </div>
        @endif
    </div>
</div>
@endsection
