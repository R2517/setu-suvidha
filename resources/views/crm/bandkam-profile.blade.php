@extends('layouts.app')
@section('title', '{{ $registration->applicant_name }} — बांधकाम कामगार CRM')

@section('content')
<div x-data="profileApp()" class="min-h-screen bg-gray-50 dark:bg-gray-950">
    {{-- CRM Header --}}
    <div class="bg-gradient-to-r from-teal-600 to-emerald-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold flex items-center gap-2"><i data-lucide="hard-hat" class="w-5 h-5"></i> बांधकाम कामगार CRM</h1>
                    <p class="text-xs text-white/70">Customer Registration & Schemes Management</p>
                </div>
                <a href="{{ route('bandkam') }}" class="inline-flex items-center gap-1 text-xs bg-white/15 hover:bg-white/25 px-3 py-1.5 rounded-lg transition">
                    <i data-lucide="arrow-left" class="w-3 h-3"></i> Customer List
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
        <div class="mb-4 p-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium">{{ session('success') }}</div>
        @endif

        {{-- ① Customer Header Card --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="user" class="w-7 h-7 text-white"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $registration->applicant_name }}</h2>
                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center gap-1"><i data-lucide="phone" class="w-3 h-3"></i> {{ $registration->mobile_number }}</span>
                        @if($registration->aadhar_number)
                        <span class="inline-flex items-center gap-1"><i data-lucide="fingerprint" class="w-3 h-3"></i> {{ $registration->aadhar_number }}</span>
                        @endif
                        @if($registration->village)
                        <span class="inline-flex items-center gap-1"><i data-lucide="map-pin" class="w-3 h-3"></i> {{ $registration->village }}{{ $registration->taluka ? ', ' . $registration->taluka : '' }}{{ $registration->district ? ', ' . $registration->district : '' }}</span>
                        @endif
                    </div>
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $registration->registration_type === 'new' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ ucfirst($registration->registration_type) }}
                        </span>
                        @php
                            $statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'activated' => 'bg-green-100 text-green-700', 'expired' => 'bg-red-100 text-red-700'];
                        @endphp
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $statusColors[$registration->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $registration->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($registration->payment_status === 'partial' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($registration->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ② Payment Details --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="indian-rupee" class="w-4 h-4 text-teal-600"></i> Payment Details
                </h3>
                <button @click="editPayment = !editPayment" class="text-xs text-teal-600 hover:text-teal-700 font-semibold">
                    <span x-text="editPayment ? 'Cancel' : 'Edit'"></span>
                </button>
            </div>
            <form method="POST" action="{{ route('bandkam.payment', $registration->id) }}">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">रक्कम (Amount) ₹</label>
                        <input type="number" name="amount" value="{{ $registration->amount }}" :disabled="!editPayment" step="1"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 text-sm disabled:bg-gray-50 disabled:text-gray-500 dark:bg-gray-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">प्राप्त (Received) ₹</label>
                        <input type="number" name="received_amount" value="{{ $registration->received_amount }}" :disabled="!editPayment" step="1"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 text-sm disabled:bg-gray-50 disabled:text-gray-500 dark:bg-gray-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">बाकी (Pending) ₹</label>
                        <input type="text" value="₹{{ number_format($registration->amount - $registration->received_amount, 0) }}" disabled
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-50 dark:bg-gray-800 text-sm text-gray-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">पेमेंट मोड</label>
                        <select name="payment_mode" :disabled="!editPayment" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 text-sm disabled:bg-gray-50 disabled:text-gray-500 dark:bg-gray-800">
                            @foreach(['cash' => 'Cash', 'online' => 'Online', 'upi' => 'UPI', 'cheque' => 'Cheque'] as $val => $label)
                            <option value="{{ $val }}" {{ $registration->payment_mode === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div x-show="editPayment" x-transition class="mt-4">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-500 shadow hover:shadow-md transition">
                        Save Payment
                    </button>
                </div>
            </form>
        </div>

        {{-- ③ Date Management --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-6">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-teal-600"></i> Date Management
            </h3>
            <form method="POST" action="{{ route('bandkam.dates', $registration->id) }}">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">File Received Date</label>
                        <input type="date" name="form_date" value="{{ $registration->form_date?->format('Y-m-d') }}"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Online Date</label>
                        <input type="date" name="online_date" value="{{ $registration->online_date?->format('Y-m-d') }}"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Appointment Date (Thumb)</label>
                        <input type="date" name="appointment_date" value="{{ $registration->appointment_date?->format('Y-m-d') }}"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Application Number (MH...)</label>
                        <input type="text" name="application_number" value="{{ $registration->application_number }}" placeholder="MH-AMR-0120"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Activation Date</label>
                        <input type="date" name="activation_date" value="{{ $registration->activation_date?->format('Y-m-d') }}"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Expiry Date (Auto — 1 Year)</label>
                        <input type="text" value="{{ $registration->expiry_date?->format('d-m-Y') ?? 'Auto-calculated' }}" disabled
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-50 dark:bg-gray-800 text-sm text-gray-500">
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-500 shadow hover:shadow-md transition">
                    <i data-lucide="check" class="w-3 h-3"></i> Save Dates
                </button>
            </form>
        </div>

        {{-- ④ Schemes & Kits Section --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="gift" class="w-4 h-4 text-teal-600"></i> योजना / Schemes & Kits
                </h3>
                <button @click="showSchemeForm = !showSchemeForm" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-500 hover:shadow-md transition">
                    <span x-text="showSchemeForm ? '✕ बंद करा' : '+ Add Scheme/Kit'"></span>
                </button>
            </div>

            {{-- New Scheme Form --}}
            <div x-show="showSchemeForm" x-transition class="bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 p-5 mb-5">
                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">नवीन Scheme / Kit Entry</h4>
                <form method="POST" action="{{ route('bandkam.schemes.store', $registration->id) }}">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">SCHEME / KIT TYPE</label>
                            <select name="scheme_type" x-model="newSchemeType" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                <option value="essential_kit">Essential Kit</option>
                                <option value="safety_kit">Safety Kit</option>
                                <option value="scholarship">Scholarship</option>
                                <option value="pregnancy">Pregnancy</option>
                                <option value="marriage">Marriage</option>
                                <option value="death">Death</option>
                            </select>
                        </div>
                        <div x-show="newSchemeType === 'scholarship'">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">SCHOLARSHIP CATEGORY</label>
                            <select name="scholarship_category" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                <option value="">Select</option>
                                <option value="1_to_4">इयत्ता 1 ते 4</option>
                                <option value="5_to_10">इयत्ता 5 ते 10</option>
                                <option value="11_to_12">इयत्ता 11 ते 12</option>
                                <option value="graduation">Graduation</option>
                                <option value="iti">ITI</option>
                                <option value="engineering">Engineering</option>
                                <option value="10th_50_above">10th - 50% Above</option>
                                <option value="12th_50_above">12th - 50% Above</option>
                            </select>
                        </div>
                        <div x-show="newSchemeType === 'scholarship'">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">STUDENT NAME</label>
                            <input type="text" name="student_name" placeholder="Student Name" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                        </div>
                        <div x-show="!['essential_kit','safety_kit'].includes(newSchemeType)">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">BENEFICIARY NAME</label>
                            <input type="text" name="beneficiary_name" placeholder="Beneficiary" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">APPLY DATE</label>
                            <input type="date" name="apply_date" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                        </div>
                        <div x-show="['essential_kit','safety_kit'].includes(newSchemeType)">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">DELIVERY DATE</label>
                            <input type="date" name="delivery_date" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                        </div>
                        <div x-show="!['essential_kit','safety_kit'].includes(newSchemeType)">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">APPOINTMENT DATE</label>
                            <input type="date" name="appointment_date" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">YEAR</label>
                            <input type="text" name="year" value="{{ date('Y') }}" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">रक्कम (AMOUNT) ₹</label>
                            <input type="number" name="amount" value="0" step="1" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">COMMISSION %</label>
                            <input type="number" name="commission_percent" value="0" step="0.1" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">COMMISSION (AUTO) ₹</label>
                            <input type="text" value="₹0" disabled class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-50 dark:bg-gray-800 text-sm text-gray-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">प्राप्त रक्कम (RECEIVED) ₹</label>
                            <input type="number" name="received_amount" value="0" step="1" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">STATUS</label>
                            <select name="status" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                <option value="pending">Pending</option>
                                <option value="applied">Applied</option>
                                <option value="approved">Approved</option>
                                <option value="received">Received</option>
                                <option value="delivered">Delivered</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">पेमेंट मोड</label>
                            <select name="payment_mode" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                <option value="cash">Cash</option>
                                <option value="online">Online</option>
                                <option value="upi">UPI</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-500 shadow hover:shadow-md transition">
                        ₹ Save Scheme
                    </button>
                </form>
            </div>

            {{-- Schemes Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase">#</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase">TYPE</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase">DETAILS</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase">APPLY DATE</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase">APPT/DELIVERY</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase">AMOUNT</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase">COMM</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase">RECEIVED</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase">PAYMENT</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase">STATUS</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($registration->schemes as $idx => $scheme)
                        @php
                            $typeLabels = ['safety_kit' => 'Safety Kit', 'essential_kit' => 'Essential Kit', 'scholarship' => 'Scholarship', 'pregnancy' => 'Pregnancy', 'marriage' => 'Marriage', 'death' => 'Death'];
                            $typeColors = ['safety_kit' => 'bg-orange-100 text-orange-700', 'essential_kit' => 'bg-green-100 text-green-700', 'scholarship' => 'bg-blue-100 text-blue-700', 'pregnancy' => 'bg-pink-100 text-pink-700', 'marriage' => 'bg-purple-100 text-purple-700', 'death' => 'bg-gray-200 text-gray-700'];
                            $statusLabels = ['pending' => 'Pending', 'applied' => 'Applied', 'approved' => 'Approved', 'received' => 'Received', 'delivered' => 'Delivered', 'rejected' => 'Rejected'];
                            $statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'applied' => 'bg-blue-100 text-blue-700', 'approved' => 'bg-green-100 text-green-700', 'received' => 'bg-teal-100 text-teal-700', 'delivered' => 'bg-emerald-100 text-emerald-700', 'rejected' => 'bg-red-100 text-red-700'];
                        @endphp
                        <tr class="{{ $scheme->status === 'pending' ? 'bg-amber-50/30' : '' }} hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-3 py-3 text-gray-400 text-xs">{{ $idx + 1 }}</td>
                            <td class="px-3 py-3">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $typeColors[$scheme->scheme_type] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $typeLabels[$scheme->scheme_type] ?? ucfirst($scheme->scheme_type) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-xs text-gray-600">
                                @if($scheme->scholarship_category)
                                    {{ str_replace('_', ' ', ucfirst($scheme->scholarship_category)) }}
                                @elseif($scheme->beneficiary_name)
                                    {{ $scheme->beneficiary_name }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-3 py-3 text-xs text-gray-500">{{ $scheme->apply_date?->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-3 py-3 text-xs text-gray-500">
                                {{ $scheme->delivery_date?->format('d/m/Y') ?? ($scheme->appointment_date?->format('d/m/Y') ?? '—') }}
                            </td>
                            <td class="px-3 py-3 text-xs font-medium text-gray-700">₹{{ number_format($scheme->amount, 0) }}</td>
                            <td class="px-3 py-3 text-xs text-gray-500">₹{{ number_format($scheme->commission_amount, 0) }} ({{ $scheme->commission_percent }}%)</td>
                            <td class="px-3 py-3 text-xs font-medium text-gray-700">₹{{ number_format($scheme->received_amount, 0) }}</td>
                            <td class="px-3 py-3">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $scheme->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($scheme->payment_status === 'partial' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($scheme->payment_status) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $statusColors[$scheme->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$scheme->status] ?? ucfirst($scheme->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-1">
                                    <a href="#scheme-edit-{{ $scheme->id }}" @click="editingScheme = editingScheme === {{ $scheme->id }} ? null : {{ $scheme->id }}" class="p-1 rounded text-teal-600 hover:bg-teal-50 transition" title="Edit">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <form method="POST" action="{{ route('bandkam.schemes.destroy', $scheme->id) }}" onsubmit="return confirm('Scheme हटवायचे?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1 rounded text-red-500 hover:bg-red-50 transition" title="Delete">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        {{-- Inline Edit Row --}}
                        <tr x-show="editingScheme === {{ $scheme->id }}" x-transition>
                            <td colspan="11" class="px-3 py-3 bg-teal-50/50 dark:bg-teal-900/10">
                                <form method="POST" action="{{ route('bandkam.schemes.update', $scheme->id) }}" class="flex flex-wrap items-end gap-3">
                                    @csrf @method('PUT')
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-0.5">Received ₹</label>
                                        <input type="number" name="received_amount" value="{{ $scheme->received_amount }}" step="1" class="w-24 px-2 py-1.5 rounded border border-gray-300 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-0.5">Amount ₹</label>
                                        <input type="number" name="amount" value="{{ $scheme->amount }}" step="1" class="w-24 px-2 py-1.5 rounded border border-gray-300 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-0.5">Comm %</label>
                                        <input type="number" name="commission_percent" value="{{ $scheme->commission_percent }}" step="0.1" class="w-20 px-2 py-1.5 rounded border border-gray-300 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-0.5">Pay Status</label>
                                        <select name="payment_status" class="px-2 py-1.5 rounded border border-gray-300 text-xs">
                                            @foreach(['unpaid','partial','paid'] as $ps)
                                            <option value="{{ $ps }}" {{ $scheme->payment_status === $ps ? 'selected' : '' }}>{{ ucfirst($ps) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-0.5">Status</label>
                                        <select name="status" class="px-2 py-1.5 rounded border border-gray-300 text-xs">
                                            @foreach(['pending','applied','approved','received','delivered','rejected'] as $ss)
                                            <option value="{{ $ss }}" {{ $scheme->status === $ss ? 'selected' : '' }}>{{ ucfirst($ss) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-0.5">Beneficiary</label>
                                        <input type="text" name="beneficiary_name" value="{{ $scheme->beneficiary_name }}" class="w-28 px-2 py-1.5 rounded border border-gray-300 text-xs">
                                    </div>
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-teal-500 hover:bg-teal-600 transition">Save</button>
                                    <button type="button" @click="editingScheme = null" class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 transition">Cancel</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="11" class="px-4 py-8 text-center text-gray-400 text-xs">कोणतीही योजना नाही. "Add Scheme/Kit" बटणावर क्लिक करा.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function profileApp() {
    return {
        editPayment: false,
        showSchemeForm: false,
        newSchemeType: 'essential_kit',
        editingScheme: null,
    }
}
</script>
@endpush
@endsection
