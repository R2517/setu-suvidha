@extends('layouts.app')
@section('title', 'Farmer Card Orders — Admin')
@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Farmer ID Card — Public Orders</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">शेतकरी ओळखपत्र ऑनलाइन — सर्व ऑर्डर्स</p>
            </div>
            <a href="{{ route('admin.pricing') }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2 rounded-xl text-sm transition">
                <i data-lucide="indian-rupee" class="w-4 h-4"></i> Manage Pricing
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
            @foreach([
                ['label' => 'Total Orders', 'value' => $stats['total'], 'icon' => 'list', 'color' => 'blue'],
                ['label' => 'Paid', 'value' => $stats['paid'], 'icon' => 'check-circle', 'color' => 'green'],
                ['label' => 'Pending', 'value' => $stats['pending'], 'icon' => 'clock', 'color' => 'amber'],
                ['label' => 'Purged (7d)', 'value' => $stats['purged'], 'icon' => 'trash-2', 'color' => 'red'],
                ['label' => 'Revenue', 'value' => '₹' . number_format($stats['revenue'], 2), 'icon' => 'wallet', 'color' => 'emerald'],
            ] as $s)
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-{{ $s['color'] }}-100 dark:bg-{{ $s['color'] }}-900/30 flex items-center justify-center">
                        <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color'] }}-600"></i>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $s['label'] }}</span>
                </div>
                <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $s['value'] }}</div>
            </div>
            @endforeach
        </div>

        {{-- Search + Filter --}}
        <form method="GET" class="flex flex-col sm:flex-row gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, mobile, transaction no..." class="flex-1 px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500">
            <select name="status" class="px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm">
                <option value="">All Status</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl text-sm transition flex items-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i> Search
            </button>
        </form>

        {{-- Orders Table --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Transaction No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Mobile</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Amount</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Downloads</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Data</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Created</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">View</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($orders as $o)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 font-mono text-xs text-green-700 dark:text-green-400 font-bold">{{ $o->transaction_no }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $o->applicant_name }}</div>
                                <div class="text-xs text-gray-400">{{ $o->name_english }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $o->mobile }}</td>
                            <td class="px-4 py-3 text-center font-semibold {{ $o->amount > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $o->amount > 0 ? '₹' . number_format($o->amount / 100, 2) : 'Free' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($o->status === 'paid')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Paid</span>
                                @elseif($o->status === 'pending')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Pending</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Failed</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $o->download_count }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($o->data_purged)
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-500">Purged</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Active</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ $o->created_at->format('d M Y, h:i A') }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($o->status === 'paid' && !$o->data_purged)
                                    <a href="{{ route('farmer-card-public.download', $o->transaction_no) }}" target="_blank" class="text-green-600 hover:text-green-700" title="View Card">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </a>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                <p>No orders yet</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
