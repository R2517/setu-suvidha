@extends('layouts.app')
@section('title', 'Error Logs — Admin')

@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')

    <div class="flex-1 p-6 lg:p-8 bg-gray-50 dark:bg-gray-950 overflow-x-hidden">
        <div class="max-w-7xl mx-auto">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="bug" class="w-6 h-6 text-red-500"></i> Error Logs
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Website errors & exceptions automatically captured</p>
                </div>
                <form action="{{ route('admin.error-logs.clear-resolved') }}" method="POST" onsubmit="return confirm('Clear all resolved logs?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Clear Resolved
                    </button>
                </form>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <i data-lucide="layers" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</div>
                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Total Errors</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                            <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['today']) }}</div>
                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Today</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['unresolved']) }}</div>
                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Unresolved</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                            <i data-lucide="flame" class="w-5 h-5 text-rose-600"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['critical']) }}</div>
                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Critical</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <form method="GET" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 mb-6">
                <div class="flex flex-wrap items-center gap-3">
                    <select name="level" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                        <option value="">All Levels</option>
                        @foreach(['emergency','critical','error','warning','notice','info'] as $lvl)
                        <option value="{{ $lvl }}" {{ request('level') === $lvl ? 'selected' : '' }}>{{ ucfirst($lvl) }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                        <option value="">All Status</option>
                        <option value="unresolved" {{ request('status') === 'unresolved' ? 'selected' : '' }}>Unresolved</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search message, file, URL..." class="flex-1 min-w-[200px] px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold transition">
                        <i data-lucide="search" class="w-4 h-4 inline"></i> Filter
                    </button>
                    @if(request()->hasAny(['level','status','search']))
                    <a href="{{ route('admin.error-logs') }}" class="px-3 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 transition">Clear</a>
                    @endif
                </div>
            </form>

            {{-- Error Logs Table --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                @if($logs->isEmpty())
                <div class="text-center py-16">
                    <i data-lucide="check-circle-2" class="w-12 h-12 text-green-400 mx-auto mb-3"></i>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">No errors found!</h3>
                    <p class="text-sm text-gray-500">Your application is running smoothly.</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Level</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Error</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Location</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">User</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Time</th>
                                <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                            @foreach($logs as $log)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition {{ $log->is_resolved ? 'opacity-50' : '' }}"
                                x-data="{ expanded: false }">
                                <td class="px-4 py-3">
                                    @php
                                        $levelColors = [
                                            'emergency' => 'bg-rose-100 text-rose-700',
                                            'critical' => 'bg-red-100 text-red-700',
                                            'error' => 'bg-orange-100 text-orange-700',
                                            'warning' => 'bg-amber-100 text-amber-700',
                                            'notice' => 'bg-blue-100 text-blue-700',
                                            'info' => 'bg-gray-100 text-gray-700',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold {{ $levelColors[$log->level] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ strtoupper($log->level) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 max-w-xs">
                                    <button @click="expanded = !expanded" class="text-left w-full">
                                        <p class="text-xs font-semibold text-gray-900 dark:text-white truncate max-w-[300px]">{{ $log->message }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $log->method }} {{ Str::limit($log->url, 40) }}</p>
                                    </button>
                                    <div x-show="expanded" x-collapse class="mt-2 bg-gray-900 text-gray-200 rounded-lg p-3 text-[11px] font-mono max-h-48 overflow-y-auto whitespace-pre-wrap">{{ Str::limit($log->trace, 2000) }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-[11px] text-gray-600 dark:text-gray-400 truncate max-w-[180px]" title="{{ $log->file }}">{{ $log->file ? basename($log->file) : '-' }}</p>
                                    @if($log->line)
                                    <p class="text-[10px] text-gray-400">Line {{ $log->line }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($log->user)
                                    <p class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $log->user->name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $log->ip }}</p>
                                    @else
                                    <p class="text-[10px] text-gray-400">{{ $log->ip ?? 'System' }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $log->created_at->format('d M, H:i') }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <form action="{{ route('admin.error-logs.resolve', $log->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="{{ $log->is_resolved ? 'Reopen' : 'Resolve' }}" class="p-1.5 rounded-lg {{ $log->is_resolved ? 'text-amber-500 hover:bg-amber-50' : 'text-green-500 hover:bg-green-50' }} transition">
                                                <i data-lucide="{{ $log->is_resolved ? 'rotate-ccw' : 'check-circle' }}" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.error-logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Delete this log?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete" class="p-1.5 rounded-lg text-red-400 hover:bg-red-50 transition">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
