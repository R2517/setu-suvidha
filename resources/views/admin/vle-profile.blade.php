@extends('layouts.app')
@section('title', $profile->full_name . ' — VLE Profile — Admin')
@section('content')
<div class="flex min-h-screen" x-data="{ showBalance: false }">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-6 lg:p-8 overflow-x-hidden">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-4">
            <a href="{{ route('admin.vles') }}" class="hover:text-amber-600 transition">VLE व्यवस्थापन</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-gray-700 dark:text-gray-300">{{ $profile->full_name }}</span>
        </div>

        @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-400">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-sm text-red-700 dark:text-red-400">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left: Profile Info --}}
            <div class="lg:col-span-1 space-y-5">
                {{-- Profile Card --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 text-center">
                    <div class="w-20 h-20 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mx-auto mb-3">
                        @if($profile->profile_pic)
                        <img src="{{ asset('storage/' . $profile->profile_pic) }}" class="w-20 h-20 rounded-full object-cover">
                        @else
                        <i data-lucide="user" class="w-8 h-8 text-amber-600"></i>
                        @endif
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $profile->full_name }}</h2>
                    @if($profile->full_name_mr)
                    <p class="text-sm text-gray-500">{{ $profile->full_name_mr }}</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">{{ $profile->email }}</p>
                    @if($profile->mobile)
                    <p class="text-xs text-gray-400">{{ $profile->mobile }}</p>
                    @endif

                    <div class="flex items-center justify-center gap-2 mt-4">
                        <form method="POST" action="{{ route('admin.vles.toggle', $profile->id) }}">@csrf
                            <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold {{ $profile->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $profile->is_active ? 'सक्रिय' : 'निष्क्रिय' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.vles.approval', $profile->id) }}">@csrf
                            <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold {{ $profile->is_public_approved ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }}">{{ $profile->is_public_approved ? 'Public ✓' : 'Not Public' }}</button>
                        </form>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">वॉलेट शिल्लक</span>
                        <span class="text-lg font-bold {{ $profile->wallet_balance < 30 ? 'text-red-600' : 'text-green-600' }}">₹{{ number_format($profile->wallet_balance, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">फॉर्म्स Created</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $formCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Billing Sales</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $salesCount }} (₹{{ number_format($salesTotal, 2) }})</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Subscription</span>
                        @if($subscription)
                        <span class="text-xs font-bold text-indigo-600">{{ $subscription->plan->name ?? '-' }} ({{ $subscription->status }})</span>
                        @else
                        <span class="text-xs text-gray-400">No active plan</span>
                        @endif
                    </div>
                </div>

                {{-- Balance Adjust --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                    <button @click="showBalance = !showBalance" class="w-full px-5 py-3 flex items-center justify-between text-sm font-bold text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <span class="flex items-center gap-2"><i data-lucide="wallet" class="w-4 h-4 text-amber-500"></i> Balance Adjust</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400" :class="showBalance && 'rotate-180'" style="transition: transform 0.2s"></i>
                    </button>
                    <div x-show="showBalance" x-transition class="px-5 pb-5 border-t border-gray-100 dark:border-gray-800">
                        <form method="POST" action="{{ route('admin.vles.balance', $profile->id) }}" class="space-y-3 mt-3">
                            @csrf
                            <div class="flex gap-2">
                                <label class="flex items-center gap-1.5 text-xs"><input type="radio" name="type" value="add" checked class="text-green-600"> <span class="text-green-600 font-bold">+ जोडा</span></label>
                                <label class="flex items-center gap-1.5 text-xs"><input type="radio" name="type" value="reduce" class="text-red-600"> <span class="text-red-600 font-bold">- कमी करा</span></label>
                            </div>
                            <input type="number" name="amount" step="0.01" min="0.01" required placeholder="रक्कम ₹" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                            <input type="text" name="reason" required placeholder="कारण (e.g. Bonus, Refund)" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                            <button type="submit" class="w-full px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition">Update Balance</button>
                        </form>
                    </div>
                </div>

                {{-- Business Details --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 space-y-2">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Business Details</h3>
                    @php $fields = [
                        'दुकान' => $profile->shop_name,
                        'प्रकार' => $profile->shop_type,
                        'जिल्हा' => $profile->district,
                        'तालुका' => $profile->taluka,
                        'पत्ता' => $profile->address,
                        'CSC ID' => $profile->csc_id,
                        'SETU ID' => $profile->setu_id,
                        'Bank' => $profile->bank_name,
                        'UPI' => $profile->upi_id,
                    ]; @endphp
                    @foreach($fields as $label => $val)
                    @if($val)
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-[11px] text-gray-400 shrink-0">{{ $label }}</span>
                        <span class="text-xs text-gray-900 dark:text-white text-right">{{ $val }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- Right: Transactions --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="history" class="w-4 h-4 text-amber-500"></i> अलीकडील व्यवहार</h2>
                        <span class="text-xs text-gray-400">Last 20</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500">वर्णन</th>
                                    <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500">प्रकार</th>
                                    <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500">रक्कम</th>
                                    <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500">शिल्लक</th>
                                    <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500">तारीख</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($transactions as $txn)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-2.5 text-gray-700 dark:text-gray-300 text-xs">{{ $txn->description ?? '-' }}</td>
                                    <td class="px-4 py-2.5 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $txn->type === 'credit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $txn->type === 'credit' ? 'जमा' : 'खर्च' }}</span>
                                    </td>
                                    <td class="px-4 py-2.5 text-right font-bold text-xs {{ $txn->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">{{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}</td>
                                    <td class="px-4 py-2.5 text-right text-xs text-gray-500">₹{{ number_format($txn->balance_after, 2) }}</td>
                                    <td class="px-4 py-2.5 text-right text-[10px] text-gray-400">{{ $txn->created_at->format('d M, h:i A') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400">अद्याप कोणतेही व्यवहार नाहीत</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
