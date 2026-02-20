@extends('layouts.app')
@section('title', 'Contact Requests — Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="contactAdmin()">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i data-lucide="inbox" class="w-5 h-5 text-amber-500"></i> Contact Requests
            </h1>
            <p class="text-xs text-gray-500 mt-0.5">{{ $newCount }} new request(s)</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-xs text-gray-500 hover:text-amber-600 flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-3 h-3"></i> Back to Admin
        </a>
    </div>

    @if($requests->count() === 0)
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-12 text-center">
        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
        <p class="text-sm text-gray-400">No contact requests yet.</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($requests as $cr)
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden {{ $cr->status === 'new' ? 'border-l-4 border-l-amber-500' : '' }}">
            <div class="px-5 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            @if($cr->status === 'new')
                            <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                            @elseif($cr->status === 'read')
                            <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                            @else
                            <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                            @endif
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $cr->subject }}</h3>
                        </div>
                        <div class="flex items-center gap-3 text-[11px] text-gray-500 mb-2">
                            <span class="flex items-center gap-1"><i data-lucide="user" class="w-3 h-3"></i> {{ $cr->name }}</span>
                            <span class="flex items-center gap-1"><i data-lucide="mail" class="w-3 h-3"></i> {{ $cr->email }}</span>
                            <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i> {{ $cr->created_at->diffForHumans() }}</span>
                            <span class="flex items-center gap-1"><i data-lucide="globe" class="w-3 h-3"></i> {{ $cr->ip_address }}</span>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $cr->message }}</p>

                        @if($cr->attachment_path)
                        <a href="{{ asset('storage/' . $cr->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 mt-2 text-xs text-amber-600 hover:text-amber-700 font-medium bg-amber-50 dark:bg-amber-900/20 px-3 py-1.5 rounded-lg">
                            <i data-lucide="paperclip" class="w-3 h-3"></i> View Attachment
                        </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <select @change="updateStatus({{ $cr->id }}, $event.target.value)" class="text-xs border border-gray-200 dark:border-gray-700 rounded-lg px-2 py-1.5 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                            <option value="new" {{ $cr->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="read" {{ $cr->status === 'read' ? 'selected' : '' }}>Read</option>
                            <option value="replied" {{ $cr->status === 'replied' ? 'selected' : '' }}>Replied</option>
                        </select>
                        <button @click="deleteRequest({{ $cr->id }})" class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $requests->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function contactAdmin() {
    return {
        async updateStatus(id, status) {
            await fetch(`/admin/contact-requests/${id}/status`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ status })
            });
        },
        async deleteRequest(id) {
            if (!confirm('Delete this request?')) return;
            await fetch(`/admin/contact-requests/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            window.location.reload();
        }
    }
}
</script>
@endpush
