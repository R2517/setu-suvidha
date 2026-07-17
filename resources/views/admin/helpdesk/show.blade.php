@extends('layouts.app')
@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-6 lg:p-8 h-[calc(100vh-80px)] flex flex-col">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.helpdesk.index') }}" class="p-2 bg-white rounded-lg shadow-sm border border-gray-200 text-gray-500 hover:text-gray-900 transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    Ticket #{{ $ticket->id }}
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                        {{ $ticket->type === 'grievance' ? 'bg-red-100 text-red-700' : ($ticket->type === 'suggestion' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700') }}">
                        {{ $ticket->type }}
                    </span>
                </h1>
                <p class="text-xs text-gray-500 mt-0.5">VLE: <span class="font-bold text-gray-900">{{ $ticket->user->name }}</span> ({{ $ticket->user->mobile }}) • Wallet: <span class="text-green-600 font-bold">₹{{ number_format($ticket->user->wallet_balance ?? 0, 2) }}</span></p>
            </div>
        </div>

        <form action="{{ route('admin.helpdesk.reply', $ticket->id) }}" method="POST" class="flex items-center gap-2">
            @csrf
            <select name="status" onchange="this.form.submit()" class="px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm font-semibold capitalize focus:ring-2 focus:ring-blue-500">
                <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </form>
    </div>

    <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col overflow-hidden">
        {{-- Header subject --}}
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-semibold text-gray-800">{{ $ticket->subject ?: 'No Subject' }}</h2>
        </div>

        {{-- Chat Area --}}
        <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 bg-slate-50">
            {{-- Initial Message --}}
            <div class="flex items-start gap-3 md:gap-4 max-w-4xl">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0 shadow-sm border border-blue-200">
                    <i data-lucide="user" class="w-4 h-4 md:w-5 md:h-5 text-blue-600"></i>
                </div>
                <div class="flex-1 bg-white rounded-2xl rounded-tl-none px-4 py-3 md:px-5 md:py-4 shadow-sm border border-gray-100">
                    <div class="text-[10px] font-bold text-gray-400 mb-1">{{ $ticket->user->name }}</div>
                    <p class="text-gray-800 whitespace-pre-wrap text-sm leading-relaxed">{{ $ticket->message }}</p>
                    @if($ticket->attachment_path)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:underline bg-blue-50 px-3 py-1.5 rounded-lg">
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
            <div class="flex items-start gap-3 md:gap-4 max-w-4xl {{ $msg->sender_type === 'admin' ? 'flex-row-reverse ml-auto' : '' }}">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center shrink-0 shadow-sm
                    {{ $msg->sender_type === 'admin' ? 'bg-amber-100 border border-amber-200' : 'bg-blue-100 border border-blue-200' }}">
                    <i data-lucide="{{ $msg->sender_type === 'admin' ? 'shield' : 'user' }}" 
                       class="w-4 h-4 md:w-5 md:h-5 {{ $msg->sender_type === 'admin' ? 'text-amber-600' : 'text-blue-600' }}"></i>
                </div>
                <div class="flex-1 bg-white rounded-2xl px-4 py-3 md:px-5 md:py-4 shadow-sm border border-gray-100
                    {{ $msg->sender_type === 'admin' ? 'rounded-tr-none bg-amber-50/30' : 'rounded-tl-none' }}">
                    <div class="text-[10px] font-bold text-gray-400 mb-1">{{ $msg->sender_type === 'admin' ? 'Admin' : $ticket->user->name }}</div>
                    <p class="text-gray-800 whitespace-pre-wrap text-sm leading-relaxed">{{ $msg->message }}</p>
                    @if($msg->attachment_path)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <a href="{{ Storage::url($msg->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:underline bg-blue-50 px-3 py-1.5 rounded-lg">
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
        <div class="p-4 border-t border-gray-200 bg-white">
            <form action="{{ route('admin.helpdesk.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
                @csrf
                <textarea name="message" rows="3" placeholder="Type your reply to the VLE..." 
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                
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
    </div>
    </div>
</div>

<script>
    // Scroll chat to bottom on load
    document.addEventListener('DOMContentLoaded', () => {
        const chatArea = document.querySelector('.overflow-y-auto');
        if (chatArea) chatArea.scrollTop = chatArea.scrollHeight;
    });
</script>
@endsection
