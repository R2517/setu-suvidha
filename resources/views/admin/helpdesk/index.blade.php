@extends('layouts.app')
@section('title', 'Helpdesk Tickets')

@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-6 lg:p-8 overflow-x-hidden" x-data="adminHelpdesk()">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Helpdesk Tickets</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-600">ID</th>
                        <th class="px-6 py-4 font-bold text-gray-600">VLE Info</th>
                        <th class="px-6 py-4 font-bold text-gray-600">Type / Subject</th>
                        <th class="px-6 py-4 font-bold text-gray-600">Date</th>
                        <th class="px-6 py-4 font-bold text-gray-600">Status</th>
                        <th class="px-6 py-4 font-bold text-gray-600 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-900">#{{ $ticket->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $ticket->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $ticket->user->mobile }}</div>
                            <div class="text-[10px] text-green-600 font-bold mt-0.5">₹ {{ number_format($ticket->user->wallet_balance ?? 0, 2) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                {{ $ticket->type === 'grievance' ? 'bg-red-100 text-red-700' : ($ticket->type === 'suggestion' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $ticket->type }}
                            </span>
                            <div class="mt-1 text-gray-700 max-w-xs truncate">{{ $ticket->subject ?: Str::limit($ticket->message, 30) }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $ticket->created_at->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize
                                {{ $ticket->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $ticket->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.helpdesk.show', $ticket->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-semibold transition">
                                <i data-lucide="message-square" class="w-4 h-4"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No tickets found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $tickets->links() }}
        </div>
    </div>
    </div>
</div>

<script>
function adminHelpdesk() {
    return {
        // Auto-refresh mechanism to make it "live" could be added here
        init() {
            // Optional: Reload page every 60 seconds if we are on page 1
            if(window.location.search === '' || window.location.search.includes('page=1')) {
                setInterval(() => {
                    window.location.reload();
                }, 60000);
            }
        }
    }
}
</script>
@endsection
