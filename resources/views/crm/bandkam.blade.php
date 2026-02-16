@extends('layouts.app')
@section('title', 'बांधकाम कामगार CRM — SETU Suvidha')
@section('content')
<div x-data="bandkamApp()" class="min-h-screen bg-gray-50 dark:bg-gray-950">
    {{-- CRM Header --}}
    <div class="bg-gradient-to-r from-teal-600 to-emerald-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold flex items-center gap-2"><i data-lucide="hard-hat" class="w-5 h-5"></i> बांधकाम कामगार CRM</h1>
                    <p class="text-xs text-white/70">Customer Registration & Schemes Management</p>
                </div>
                <a href="{{ route('management') }}" class="text-xs bg-white/15 hover:bg-white/25 px-3 py-1.5 rounded-lg transition">CRM Hub</a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
        <div class="mb-4 p-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium">{{ session('success') }}</div>
        @endif

        {{-- ① Status Summary Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
            <a href="{{ route('bandkam') }}" class="block bg-white dark:bg-gray-900 rounded-xl border-2 {{ !request('status_filter') ? 'border-teal-500 shadow-md' : 'border-gray-200 dark:border-gray-800' }} p-4 text-center hover:shadow-lg transition group">
                <i data-lucide="clipboard-list" class="w-6 h-6 mx-auto mb-1 text-teal-500"></i>
                <div class="text-2xl font-black text-teal-600">{{ $allCount }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">ALL</div>
            </a>
            <a href="{{ route('bandkam', ['status_filter' => 'pending']) }}" class="block bg-white dark:bg-gray-900 rounded-xl border-2 {{ request('status_filter') === 'pending' ? 'border-amber-500 shadow-md' : 'border-gray-200 dark:border-gray-800' }} p-4 text-center hover:shadow-lg transition">
                <i data-lucide="hourglass" class="w-6 h-6 mx-auto mb-1 text-amber-500"></i>
                <div class="text-2xl font-black text-amber-600">{{ $pendingCount }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">PENDING</div>
            </a>
            <a href="{{ route('bandkam', ['status_filter' => 'activated']) }}" class="block bg-white dark:bg-gray-900 rounded-xl border-2 {{ request('status_filter') === 'activated' ? 'border-green-500 shadow-md' : 'border-gray-200 dark:border-gray-800' }} p-4 text-center hover:shadow-lg transition">
                <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-1 text-green-500"></i>
                <div class="text-2xl font-black text-green-600">{{ $activatedCount }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">ACTIVATED</div>
            </a>
            <a href="{{ route('bandkam', ['status_filter' => 'expiring']) }}" class="block bg-white dark:bg-gray-900 rounded-xl border-2 {{ request('status_filter') === 'expiring' ? 'border-orange-500 shadow-md' : 'border-gray-200 dark:border-gray-800' }} p-4 text-center hover:shadow-lg transition">
                <i data-lucide="alert-triangle" class="w-6 h-6 mx-auto mb-1 text-orange-500"></i>
                <div class="text-2xl font-black text-orange-600">{{ $expiringCount }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">EXPIRING</div>
            </a>
            <a href="{{ route('bandkam', ['status_filter' => 'expired']) }}" class="block bg-white dark:bg-gray-900 rounded-xl border-2 {{ request('status_filter') === 'expired' ? 'border-red-500 shadow-md' : 'border-gray-200 dark:border-gray-800' }} p-4 text-center hover:shadow-lg transition">
                <i data-lucide="x-circle" class="w-6 h-6 mx-auto mb-1 text-red-500"></i>
                <div class="text-2xl font-black text-red-600">{{ $expiredCount }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">EXPIRED</div>
            </a>
        </div>

        {{-- ② Top Bar --}}
        <div class="flex flex-wrap items-center gap-3 mb-6">
            <button @click="showForm = !showForm" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-500 shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i> नवीन Customer
            </button>
            <button @click="showFilters = !showFilters" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:border-teal-400 transition">
                <i data-lucide="filter" class="w-4 h-4"></i> Filter
            </button>
            <form method="GET" action="{{ route('bandkam') }}" class="flex-1 max-w-sm ml-auto">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name / mobile / village..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-teal-500 transition">
                </div>
            </form>
        </div>

        {{-- ③ Filter Sidebar --}}
        <div x-show="showFilters" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-900 shadow-2xl border-r border-gray-200 dark:border-gray-800 overflow-y-auto">
            <div class="p-5">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="filter" class="w-4 h-4"></i> Filters</h3>
                    <button @click="showFilters = false" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"><i data-lucide="x" class="w-5 h-5 text-gray-500"></i></button>
                </div>
                <form method="GET" action="{{ route('bandkam') }}" id="filterForm">
                    {{-- Registration Type --}}
                    <div class="mb-5">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2">Registration Type</p>
                        @foreach(['new' => 'New', 'renewal' => 'Renewal', 'activated' => 'Activated'] as $val => $label)
                        <label class="flex items-center gap-2 py-1 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                            <input type="checkbox" name="reg_type[]" value="{{ $val }}" {{ in_array($val, (array) request('reg_type', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                    {{-- Payment Status --}}
                    <div class="mb-5">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2">Payment Status</p>
                        @foreach(['paid' => 'Paid ', 'partial' => 'Partial ', 'unpaid' => 'Unpaid '] as $val => $label)
                        <label class="flex items-center gap-2 py-1 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                            <input type="checkbox" name="pay_status[]" value="{{ $val }}" {{ in_array($val, (array) request('pay_status', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                    {{-- Payment Mode --}}
                    <div class="mb-5">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2">Payment Mode</p>
                        @foreach(['cash' => 'Cash', 'online' => 'Online', 'upi' => 'UPI', 'cheque' => 'Cheque'] as $val => $label)
                        <label class="flex items-center gap-2 py-1 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                            <input type="checkbox" name="pay_mode[]" value="{{ $val }}" {{ in_array($val, (array) request('pay_mode', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                    {{-- Schemes & Kits --}}
                    <div class="mb-5">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2">Schemes & Kits</p>
                        @foreach(['essential_kit' => 'Essential Kit', 'safety_kit' => 'Safety Kit', 'scholarship' => 'Scholarship', 'pregnancy' => 'Pregnancy', 'marriage' => 'Marriage', 'death' => 'Death'] as $val => $label)
                        <label class="flex items-center gap-2 py-1 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                            <input type="checkbox" name="scheme_type[]" value="{{ $val }}" {{ in_array($val, (array) request('scheme_type', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                    {{-- Scheme Status --}}
                    <div class="mb-5">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2">Scheme Status</p>
                        @foreach(['pending' => 'Pending', 'applied' => 'Applied', 'approved' => 'Approved', 'received' => 'Received', 'delivered' => 'Delivered', 'rejected' => 'Rejected'] as $val => $label)
                        <label class="flex items-center gap-2 py-1 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                            <input type="checkbox" name="scheme_status[]" value="{{ $val }}" {{ in_array($val, (array) request('scheme_status', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 btn-primary text-xs !py-2">Apply</button>
                        <a href="{{ route('bandkam') }}" class="flex-1 text-center py-2 text-xs font-medium text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50">Clear All</a>
                    </div>
                </form>
            </div>
        </div>
        {{-- Filter overlay --}}
        <div x-show="showFilters" @click="showFilters = false" class="fixed inset-0 bg-black/30 z-40" x-transition.opacity></div>

        {{-- ④ New Customer Entry Form --}}
        <div x-show="showForm" x-transition class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-6">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2">नवीन Customer Entry</h3>
            <form method="POST" action="{{ route('bandkam.store') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">प्रकार (TYPE)</label>
                        <select name="registration_type" x-model="regType" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                            <option value="new">New (नवीन)</option>
                            <option value="renewal">Renewal (नूतनीकरण)</option>
                            <option value="activated">Already Activated (आधीच सक्रिय)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">FILE RECEIVED DATE</label>
                        <input type="date" name="form_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">CUSTOMER नाव (NAME) *</label>
                        <input type="text" name="applicant_name" required placeholder="Full Name" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">मोबाईल (MOBILE)</label>
                        <input type="text" name="mobile_number" required maxlength="10" placeholder="10 digit" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">आधार नंबर (AADHAR)</label>
                        <input type="text" name="aadhar_number" maxlength="12" placeholder="12 digit" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">गाव (VILLAGE)</label>
                        <input type="text" name="village" placeholder="Village" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">तालुका (TALUKA)</label>
                        <input type="text" name="taluka" placeholder="Taluka" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">जिल्हा (DISTRICT)</label>
                        <input type="text" name="district" placeholder="District" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">
                            APPLICATION NUMBER (MH...)
                            <span x-show="regType === 'activated'" class="text-red-500">*</span>
                        </label>
                        <input type="text" name="application_number" placeholder="MH-AMR-0120" :required="regType === 'activated'"
                            class="w-full px-3 py-2.5 rounded-lg border text-sm bg-white dark:bg-gray-800"
                            :class="regType === 'activated' ? 'border-red-400 dark:border-red-600 ring-1 ring-red-200' : 'border-gray-300 dark:border-gray-700'">
                        <p x-show="regType === 'activated'" class="text-[9px] text-red-500 mt-0.5">Already Activated साठी Application Number आवश्यक आहे</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">रक्कम (AMOUNT) ₹</label>
                        <input type="number" name="amount" value="0" step="1" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">प्राप्त रक्कम (RECEIVED) ₹</label>
                        <input type="number" name="received_amount" value="0" step="1" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">बाकी (PENDING) ₹</label>
                        <input type="text" value="₹0" disabled class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-50 dark:bg-gray-800 text-sm text-gray-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">पेमेंट मोड</label>
                        <select name="payment_mode" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                            <option value="cash">Cash 💵</option>
                            <option value="online">Online 🏦</option>
                            <option value="upi">UPI 📱</option>
                            <option value="cheque">Cheque 📝</option>
                        </select>
                    </div>
                </div>

                {{-- Safety Kit & Essential Kit --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">SAFETY KIT APPLY करायची आहे का?</label>
                        <select name="safety_kit" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                            <option value="yes">हो (Yes - Pending)</option>
                            <option value="no">नाही (No)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">ESSENTIAL KIT APPLY करायची आहे का?</label>
                        <select name="essential_kit" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                            <option value="yes">हो (Yes - Pending)</option>
                            <option value="no">नाही (No)</option>
                        </select>
                    </div>
                </div>

                {{-- Scholarship Categories --}}
                <div class="mb-5">
                    <p class="text-[10px] font-bold text-gray-500 uppercase mb-2">शिष्यवृत्ती (SCHOLARSHIP) — कोणत्या साठी APPLY करायचे?</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['1_to_4' => 'इयत्ता 1 ते 4', '5_to_10' => 'इयत्ता 5 ते 10', '11_to_12' => 'इयत्ता 11 ते 12', 'graduation' => 'GRADUATION', 'iti' => 'ITI', 'engineering' => 'ENGINEERING', '10th_50_above' => '10TH - 50% ABOVE', '12th_50_above' => '12TH - 50% ABOVE'] as $val => $label)
                        <label class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm cursor-pointer hover:border-teal-400 transition">
                            <input type="checkbox" name="scholarships[]" value="{{ $val }}" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-500 shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all">
                    ₹ Save Customer
                </button>
            </form>
        </div>

        {{-- ⑤ Customer List Table --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Customers ({{ $allCount }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">#</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">TYPE</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">NAME</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">AADHAR</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">MOBILE</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">SCHEMES / KITS</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">VILLAGE</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">EXPIRY</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($registrations as $i => $reg)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $registrations->firstItem() + $i }}</td>
                            <td class="px-4 py-3">
                                @php $regTypeColors = ['new' => 'bg-blue-100 text-blue-700', 'renewal' => 'bg-amber-100 text-amber-700', 'activated' => 'bg-green-100 text-green-700']; @endphp
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $regTypeColors[$reg->registration_type] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $reg->registration_type === 'activated' ? 'Activated' : ucfirst($reg->registration_type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="relative" x-data="{ showPopup: false }">
                                    <button @click="showPopup = !showPopup" class="font-bold text-gray-900 dark:text-white hover:text-teal-600 transition cursor-pointer text-left">
                                        {{ $reg->applicant_name }}
                                    </button>
                                    {{-- Name Popup --}}
                                    <div x-show="showPopup" @click.away="showPopup = false" x-transition
                                         class="absolute left-0 top-8 z-30 w-80 bg-white dark:bg-gray-900 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="font-bold text-gray-900 dark:text-white">{{ $reg->applicant_name }}</span>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold
                                                @if($reg->status === 'expired') bg-red-100 text-red-600
                                                @elseif($reg->status === 'activated') bg-green-100 text-green-600
                                                @else bg-amber-100 text-amber-600 @endif">{{ ucfirst($reg->status) }}</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 dark:text-gray-400 mb-3">
                                            <div>File Date: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $reg->form_date?->format('d/m/Y') ?? '—' }}</span></div>
                                            <div>Online: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $reg->online_date?->format('d/m/Y') ?? '—' }}</span></div>
                                            <div>Appointment: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $reg->appointment_date?->format('d/m/Y') ?? '—' }}</span></div>
                                            <div>Activation: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $reg->activation_date?->format('d/m/Y') ?? '—' }}</span></div>
                                            <div>App No: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $reg->application_number ?? '—' }}</span></div>
                                            <div>Payment: <span class="font-bold {{ $reg->payment_status === 'paid' ? 'text-green-600' : ($reg->payment_status === 'partial' ? 'text-amber-600' : 'text-red-600') }}">{{ ucfirst($reg->payment_status) }}</span></div>
                                        </div>
                                        <a href="{{ route('bandkam.show', $reg->id) }}" class="block w-full text-center py-2 rounded-lg text-xs font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-500 hover:shadow-md transition">
                                            Full Profile 
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs font-mono">{{ $reg->aadhar_number ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $reg->mobile_number }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($reg->schemes as $scheme)
                                    @php
                                        $colors = ['safety_kit' => 'bg-orange-100 text-orange-700', 'essential_kit' => 'bg-green-100 text-green-700', 'scholarship' => 'bg-blue-100 text-blue-700', 'pregnancy' => 'bg-pink-100 text-pink-700', 'marriage' => 'bg-purple-100 text-purple-700', 'death' => 'bg-gray-200 text-gray-700'];
                                        $icons = ['safety_kit' => '', 'essential_kit' => '', 'scholarship' => '', 'pregnancy' => '', 'marriage' => '', 'death' => ''];
                                    @endphp
                                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-full {{ $colors[$scheme->scheme_type] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $icons[$scheme->scheme_type] ?? '' }} {{ ucfirst(str_replace('_', ' ', $scheme->scheme_type)) }}
                                    </span>
                                    @endforeach
                                    @if($reg->schemes->isEmpty())
                                    <span class="text-[10px] text-gray-400">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $reg->village ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($reg->expiry_date)
                                    @php $isExpired = $reg->expiry_date->isPast(); $isExpiring = !$isExpired && $reg->expiry_date->diffInDays(now()) <= 7; @endphp
                                    <span class="text-xs font-bold {{ $isExpired ? 'text-red-600' : ($isExpiring ? 'text-orange-600' : 'text-green-600') }}">
                                        {{ $reg->expiry_date->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('bandkam.show', $reg->id) }}" class="p-1.5 rounded-lg text-teal-600 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition" title="View Profile">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <form method="POST" action="{{ route('bandkam.destroy', $reg->id) }}" onsubmit="return confirm('हटवायचे?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="px-4 py-12 text-center text-gray-400">कोणताही Customer नाही. "नवीन Customer" बटणावर क्लिक करा.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($registrations->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-800">{{ $registrations->links() }}</div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function bandkamApp() {
    return {
        showForm: false,
        showFilters: false,
        regType: 'new',
    }
}
</script>
@endpush
@endsection
