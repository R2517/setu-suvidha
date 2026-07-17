@extends('layouts.app')
@section('title', 'My Helpdesk Tickets — SETU Suvidha')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Support Tickets</h1>
        <button onclick="document.querySelector('[x-data=helpdeskWidget]').__x.$data.togglePopup()" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-bold shadow-md hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-4 h-4 inline-block mr-1"></i> New Request
        </button>
    </div>

    @if($tickets->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
            <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="inbox" class="w-8 h-8 text-blue-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No tickets yet</h3>
            <p class="text-gray-500 dark:text-gray-400">You haven't submitted any support requests.</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($tickets as $ticket)
                <li>
                    <a href="{{ route('helpdesk.show', $ticket->id) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700/50 transition p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider
                                        {{ $ticket->type === 'grievance' ? 'bg-red-100 text-red-700' : ($ticket->type === 'suggestion' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700') }}">
                                        {{ $ticket->type }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate pr-4">
                                    {{ $ticket->subject ?: Str::limit($ticket->message, 50) }}
                                </h3>
                            </div>
                            <div>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold capitalize
                                    {{ $ticket->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $ticket->status }}
                                </span>
                            </div>
                            <div class="ml-4 text-gray-400">
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
