@extends('layouts.app')
@section('title', 'PAN Card CRM — SETU Suvidha')
@section('content')
<div x-data="panApp()" class="min-h-screen bg-gray-50 dark:bg-gray-950">
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold flex items-center gap-2"><i data-lucide="credit-card" class="w-5 h-5"></i> PAN Card CRM</h1>
                    <p class="text-xs text-white/70">New, Correction, Reprint Applications</p>
                </div>
                <a href="{{ route('management') }}" class="text-xs bg-white/15 hover:bg-white/25 px-3 py-1.5 rounded-lg transition">&larr; CRM Hub</a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
        <div class="mb-4 p-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium">{{ session('success') }}</div>
        @endif

        {{-- Status Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
            <a href="{{ route('pan-card') }}" class="block bg-white dark:bg-gray-900 rounded-xl border-2 {{ !request('status_filter') ? 'border-indigo-500 shadow-md' : 'border-gray-200 dark:border-gray-800' }} p-4 text-center hover:shadow-lg transition">
                <i data-lucide="clipboard-list" class="w-6 h-6 mx-auto mb-1 text-indigo-500"></i>
                <div class="text-2xl font-black text-indigo-600">{{ $allCount }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">ALL</div>
            </a>
            <a href="{{ route('pan-card', ['status_filter' => 'pending']) }}" class="block bg-white dark:bg-gray-900 rounded-xl border-2 {{ request('status_filter') === 'pending' ? 'border-amber-500 shadow-md' : 'border-gray-200 dark:border-gray-800' }} p-4 text-center hover:shadow-lg transition">
                <i data-lucide="hourglass" class="w-6 h-6 mx-auto mb-1 text-amber-500"></i>
                <div class="text-2xl font-black text-amber-600">{{ $pendingCount }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">PENDING</div>
            </a>
            <a href="{{ route('pan-card', ['status_filter' => 'processing']) }}" class="block bg-white dark:bg-gray-900 rounded-xl border-2 {{ request('status_filter') === 'processing' ? 'border-blue-500 shadow-md' : 'border-gray-200 dark:border-gray-800' }} p-4 text-center hover:shadow-lg transition">
                <i data-lucide="loader" class="w-6 h-6 mx-auto mb-1 text-blue-500"></i>
                <div class="text-2xl font-black text-blue-600">{{ $processingCount }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">PROCESSING</div>
            </a>
            <a href="{{ route('pan-card', ['status_filter' => 'completed']) }}" class="block bg-white dark:bg-gray-900 rounded-xl border-2 {{ request('status_filter') === 'completed' ? 'border-green-500 shadow-md' : 'border-gray-200 dark:border-gray-800' }} p-4 text-center hover:shadow-lg transition">
                <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-1 text-green-500"></i>
                <div class="text-2xl font-black text-green-600">{{ $completedCount }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">COMPLETED</div>
            </a>
            <a href="{{ route('pan-card', ['status_filter' => 'rejected']) }}" class="block bg-white dark:bg-gray-900 rounded-xl border-2 {{ request('status_filter') === 'rejected' ? 'border-red-500 shadow-md' : 'border-gray-200 dark:border-gray-800' }} p-4 text-center hover:shadow-lg transition">
                <i data-lucide="x-circle" class="w-6 h-6 mx-auto mb-1 text-red-500"></i>
                <div class="text-2xl font-black text-red-600">{{ $rejectedCount }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">REJECTED</div>
            </a>
        </div>

        {{-- Top Bar --}}
        <div class="flex flex-wrap items-center gap-3 mb-6">
            <button @click="showForm = !showForm" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-indigo-500 to-blue-500 shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i> New Application
            </button>
            <button @click="showFilters = !showFilters" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:border-indigo-400 transition">
                <i data-lucide="filter" class="w-4 h-4"></i> Filter
            </button>
            <form method="GET" action="{{ route('pan-card') }}" class="flex-1 max-w-sm ml-auto">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name / mobile / aadhar..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-indigo-500 transition">
                </div>
            </form>
        </div>

        {{-- Filter Sidebar --}}
        <div x-show="showFilters" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-900 shadow-2xl border-r border-gray-200 dark:border-gray-800 overflow-y-auto">
            <div class="p-5">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="filter" class="w-4 h-4"></i> Filters</h3>
                    <button @click="showFilters = false" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"><i data-lucide="x" class="w-5 h-5 text-gray-500"></i></button>
                </div>
                <form method="GET" action="{{ route('pan-card') }}">
                    <div class="mb-5">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2">Application Type</p>
                        @foreach(['new' => 'New', 'correction' => 'Correction', 'reprint' => 'Reprint'] as $val => $label)
                        <label class="flex items-center gap-2 py-1 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                            <input type="checkbox" name="app_type[]" value="{{ $val }}" {{ in_array($val, (array) request('app_type', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                    <div class="mb-5">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2">Payment Status</p>
                        @foreach(['paid' => 'Paid', 'partial' => 'Partial', 'unpaid' => 'Unpaid'] as $val => $label)
                        <label class="flex items-center gap-2 py-1 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                            <input type="checkbox" name="pay_status[]" value="{{ $val }}" {{ in_array($val, (array) request('pay_status', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                    <div class="mb-5">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2">Payment Mode</p>
                        @foreach(['cash' => 'Cash', 'online' => 'Online', 'upi' => 'UPI', 'cheque' => 'Cheque'] as $val => $label)
                        <label class="flex items-center gap-2 py-1 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                            <input type="checkbox" name="pay_mode[]" value="{{ $val }}" {{ in_array($val, (array) request('pay_mode', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 btn-primary text-xs !py-2">Apply</button>
                        <a href="{{ route('pan-card') }}" class="flex-1 text-center py-2 text-xs font-medium text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50">Clear</a>
                    </div>
                </form>
            </div>
        </div>
        <div x-show="showFilters" @click="showFilters = false" class="fixed inset-0 bg-black/30 z-40" x-transition.opacity></div>

        {{-- New Application Form --}}
        <div x-show="showForm" x-transition class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-6">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2">New PAN Card Application</h3>
            <form method="POST" action="{{ route('pan-card.store') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">TYPE *</label>
                        <select name="application_type" required class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                            <option value="new">New (नवीन)</option>
                            <option value="correction">Correction (दुरुस्ती)</option>
                            <option value="reprint">Reprint (रिप्रिंट)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">NAME *</label>
                        <input type="text" name="applicant_name" required placeholder="Full Name" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">MOBILE *</label>
                        <input type="text" name="mobile_number" required maxlength="10" placeholder="10 digit" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">AADHAR</label>
                        <input type="text" name="aadhar_number" maxlength="12" placeholder="12 digit" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">DOB</label>
                        <input type="date" name="dob" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">AMOUNT &#8377;</label>
                        <input type="number" name="amount" value="0" step="1" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">RECEIVED &#8377;</label>
                        <input type="number" name="received_amount" value="0" step="1" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">PAYMENT MODE</label>
                        <select name="payment_mode" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                            <option value="cash">Cash</option>
                            <option value="online">Online</option>
                            <option value="upi">UPI</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-indigo-500 to-blue-500 shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Save Application
                </button>
            </form>
        </div>

        {{-- Applications Table --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Applications ({{ $allCount }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">#</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">TYPE</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">NAME</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">MOBILE</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">AADHAR</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">AMOUNT</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">PAYMENT</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">STATUS</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">DATE</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($applications as $i => $app)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition" x-data="{ editing: false }">
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $applications->firstItem() + $i }}</td>
                            <td class="px-4 py-3">
                                @php $typeColors = ['new' => 'bg-blue-100 text-blue-700', 'correction' => 'bg-amber-100 text-amber-700', 'reprint' => 'bg-purple-100 text-purple-700']; @endphp
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $typeColors[$app->application_type] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($app->application_type) }}</span>
                            </td>
                            <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $app->applicant_name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $app->mobile_number }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs font-mono">{{ $app->aadhar_number ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-gray-700">&#8377;{{ number_format($app->amount, 0) }} / &#8377;{{ number_format($app->received_amount, 0) }}</td>
                            <td class="px-4 py-3">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $app->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($app->payment_status === 'partial' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">{{ ucfirst($app->payment_status) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @php $statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'processing' => 'bg-blue-100 text-blue-700', 'completed' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700']; @endphp
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $statusColors[$app->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($app->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $app->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <button @click="editing = !editing" class="p-1.5 rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition" title="Edit"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                                    <form method="POST" action="{{ route('pan-card.destroy', $app->id) }}" onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="Delete"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        {{-- Inline Edit Row --}}
                        <tr x-show="editing" x-transition>
                            <td colspan="10" class="px-4 py-3 bg-indigo-50/50 dark:bg-indigo-900/10">
                                <form method="POST" action="{{ route('pan-card.update', $app->id) }}" class="flex flex-wrap items-end gap-3">
                                    @csrf @method('PUT')
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-0.5">Status</label>
                                        <select name="status" class="px-2 py-1.5 rounded border border-gray-300 text-xs">
                                            @foreach(['pending','processing','completed','rejected'] as $s)
                                            <option value="{{ $s }}" {{ $app->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-0.5">Amount</label>
                                        <input type="number" name="amount" value="{{ $app->amount }}" step="1" class="w-24 px-2 py-1.5 rounded border border-gray-300 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-0.5">Received</label>
                                        <input type="number" name="received_amount" value="{{ $app->received_amount }}" step="1" class="w-24 px-2 py-1.5 rounded border border-gray-300 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-0.5">Pay Mode</label>
                                        <select name="payment_mode" class="px-2 py-1.5 rounded border border-gray-300 text-xs">
                                            @foreach(['cash','online','upi','cheque'] as $m)
                                            <option value="{{ $m }}" {{ $app->payment_mode === $m ? 'selected' : '' }}>{{ ucfirst($m) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-0.5">App No.</label>
                                        <input type="text" name="application_number" value="{{ $app->application_number }}" class="w-28 px-2 py-1.5 rounded border border-gray-300 text-xs">
                                    </div>
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-500 hover:bg-indigo-600 transition">Save</button>
                                    <button type="button" @click="editing = false" class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 transition">Cancel</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="px-4 py-12 text-center text-gray-400">No applications yet. Click "New Application".</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($applications->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-800">{{ $applications->links() }}</div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function panApp() {
    return { showForm: false, showFilters: false }
}
</script>
@endpush
@endsection
