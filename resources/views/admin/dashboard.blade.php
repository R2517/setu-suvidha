@extends('layouts.app')
@section('title', 'Admin Dashboard — SETU Suvidha')
@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-6 lg:p-8 overflow-x-hidden">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
            <span class="text-xs text-gray-400">{{ now()->format('d M Y, h:i A') }}</span>
        </div>

        {{-- Row 1: Primary Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center"><i data-lucide="users" class="w-5 h-5 text-blue-600"></i></div>
                    <span class="text-xs text-gray-500 font-medium">एकूण VLEs</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalVles }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center"><i data-lucide="user-check" class="w-5 h-5 text-green-600"></i></div>
                    <span class="text-xs text-gray-500 font-medium">सक्रिय VLEs</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeVles }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center"><i data-lucide="indian-rupee" class="w-5 h-5 text-amber-600"></i></div>
                    <span class="text-xs text-gray-500 font-medium">एकूण महसूल</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center"><i data-lucide="file-text" class="w-5 h-5 text-purple-600"></i></div>
                    <span class="text-xs text-gray-500 font-medium">एकूण फॉर्म्स</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalForms }}</p>
            </div>
        </div>

        {{-- Row 2: Secondary Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center"><i data-lucide="crown" class="w-5 h-5 text-indigo-600"></i></div>
                    <span class="text-xs text-gray-500 font-medium">सक्रिय प्लॅन्स</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activePlans }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center"><i data-lucide="clock" class="w-5 h-5 text-orange-600"></i></div>
                    <span class="text-xs text-gray-500 font-medium">Pending Approvals</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingApprovals }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center"><i data-lucide="activity" class="w-5 h-5 text-teal-600"></i></div>
                    <span class="text-xs text-gray-500 font-medium">आजचे व्यवहार</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $todayTransactions }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center"><i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i></div>
                    <span class="text-xs text-gray-500 font-medium">Low Balance (&lt;₹30)</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $lowBalanceVles }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Recent Activity --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="history" class="w-4 h-4 text-amber-500"></i> अलीकडील व्यवहार</h2>
                    <a href="{{ route('admin.transactions') }}" class="text-xs text-amber-600 hover:text-amber-700 font-medium">सर्व पहा →</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($recentActivity as $txn)
                    <div class="px-6 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-lg {{ $txn->type === 'credit' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }} flex items-center justify-center shrink-0">
                                <i data-lucide="{{ $txn->type === 'credit' ? 'arrow-down-left' : 'arrow-up-right' }}" class="w-4 h-4 {{ $txn->type === 'credit' ? 'text-green-600' : 'text-red-600' }}"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $txn->user->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $txn->description ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0 ml-3">
                            <p class="text-sm font-bold {{ $txn->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">{{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}</p>
                            <p class="text-[10px] text-gray-400">{{ $txn->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400">अद्याप कोणतेही व्यवहार नाहीत</div>
                    @endforelse
                </div>
            </div>

            {{-- Plan Breakdown + Quick Links --}}
            <div class="space-y-6">
                {{-- Plan Breakdown --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="pie-chart" class="w-4 h-4 text-indigo-500"></i> प्लॅन Breakdown</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        @forelse($planBreakdown as $pb)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $pb->plan->name ?? 'Unknown' }}</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-full">{{ $pb->count }}</span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-400 text-center py-2">अद्याप कोणतेही सक्रिय प्लॅन नाहीत</p>
                        @endforelse
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2"><i data-lucide="zap" class="w-4 h-4 text-amber-500"></i> Quick Actions</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.vles') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition text-center">
                            <i data-lucide="users" class="w-5 h-5 text-blue-600 pointer-events-none"></i>
                            <span class="text-[11px] font-medium text-blue-700 dark:text-blue-400">VLEs</span>
                        </a>
                        <a href="{{ route('admin.plans') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition text-center">
                            <i data-lucide="crown" class="w-5 h-5 text-indigo-600 pointer-events-none"></i>
                            <span class="text-[11px] font-medium text-indigo-700 dark:text-indigo-400">प्लॅन्स</span>
                        </a>
                        <a href="{{ route('admin.pricing') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/30 transition text-center">
                            <i data-lucide="indian-rupee" class="w-5 h-5 text-amber-600 pointer-events-none"></i>
                            <span class="text-[11px] font-medium text-amber-700 dark:text-amber-400">Pricing</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-center">
                            <i data-lucide="settings" class="w-5 h-5 text-gray-600 pointer-events-none"></i>
                            <span class="text-[11px] font-medium text-gray-700 dark:text-gray-400">Settings</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
