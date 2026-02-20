@extends('layouts.app')
@section('title', 'प्लॅन्स — Admin')
@section('content')
<div class="flex min-h-screen" x-data="{ showCreate: false, editPlan: null }">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-6 lg:p-8 overflow-x-hidden">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">सबस्क्रिप्शन प्लॅन्स</h1>
            <button @click="showCreate = !showCreate" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition">
                <i data-lucide="plus" class="w-4 h-4"></i> नवीन प्लॅन
            </button>
        </div>

        @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-400">{{ session('success') }}</div>
        @endif

        {{-- Create Plan Form --}}
        <div x-show="showCreate" x-transition class="mb-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">नवीन प्लॅन तयार करा</h2>
            <form method="POST" action="{{ route('admin.plans.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">नाव *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white" placeholder="e.g. Monthly Plan">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">प्रकार *</label>
                    <select name="plan_type" required class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                        <option value="monthly">Monthly (मासिक)</option>
                        <option value="quarterly">Quarterly (तिमाही)</option>
                        <option value="half_yearly">Half-Yearly (सहामाही)</option>
                        <option value="yearly">Yearly (वार्षिक)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">किंमत ₹ *</label>
                    <input type="number" name="price" step="0.01" min="0" required class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">कालावधी (दिवस) *</label>
                    <input type="number" name="duration_days" min="1" required class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Features (comma-separated)</label>
                    <input type="text" name="features" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white" placeholder="Billing, CRM, DocSlip, Forms">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Discount %</label>
                    <input type="number" name="discount_percent" step="0.01" min="0" max="100" value="0" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Trial Days</label>
                    <input type="number" name="trial_days" min="0" value="15" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Razorpay Plan ID</label>
                    <input type="text" name="razorpay_plan_id" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white" placeholder="plan_xxxxx (optional)">
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition">तयार करा</button>
                </div>
            </form>
        </div>

        {{-- Plan Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            @foreach($plans as $plan)
            @php
                $features = is_array($plan->features) ? $plan->features : (is_string($plan->features) ? json_decode($plan->features, true) ?? [] : []);
                $subs = $activeSubs[$plan->id] ?? 0;
                $typeLabels = ['monthly' => 'मासिक', 'quarterly' => 'तिमाही', 'half_yearly' => 'सहामाही', 'yearly' => 'वार्षिक'];
                $typeColors = ['monthly' => 'blue', 'quarterly' => 'indigo', 'half_yearly' => 'purple', 'yearly' => 'amber'];
                $color = $typeColors[$plan->plan_type] ?? 'gray';
            @endphp
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden {{ !$plan->is_active ? 'opacity-60' : '' }}">
                {{-- Header --}}
                <div class="px-5 pt-5 pb-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-700 dark:text-{{ $color }}-400">{{ $typeLabels[$plan->plan_type] ?? $plan->plan_type }}</span>
                        <form method="POST" action="{{ route('admin.plans.toggle', $plan->id) }}">@csrf
                            <button type="submit" class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $plan->is_active ? 'सक्रिय' : 'निष्क्रिय' }}</button>
                        </form>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ $plan->name }}</h3>
                    <div class="flex items-baseline gap-1 mt-1">
                        <span class="text-2xl font-extrabold text-gray-900 dark:text-white">₹{{ number_format($plan->price) }}</span>
                        <span class="text-xs text-gray-400">/ {{ $plan->duration_days }} दिवस</span>
                    </div>
                    @if($plan->discount_percent > 0)
                    <span class="inline-block mt-1 text-[10px] font-bold text-green-600 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">{{ $plan->discount_percent }}% OFF</span>
                    @endif
                </div>

                {{-- Features --}}
                <div class="px-5 pb-3">
                    <ul class="space-y-1.5">
                        @foreach($features as $f)
                        <li class="text-xs text-gray-600 dark:text-gray-400 flex items-center gap-1.5"><i data-lucide="check-circle" class="w-3.5 h-3.5 text-green-500 shrink-0"></i> {{ $f }}</li>
                        @endforeach
                    </ul>
                </div>

                {{-- Meta --}}
                <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-800 space-y-1">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-gray-400">Trial</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $plan->trial_days }} दिवस</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-gray-400">सक्रिय subscribers</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $subs }}</span>
                    </div>
                    @if($plan->razorpay_plan_id)
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-gray-400">Razorpay</span>
                        <span class="font-mono text-[10px] text-gray-500">{{ Str::limit($plan->razorpay_plan_id, 20) }}</span>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <button @click="editPlan = {{ $plan->id }}" class="text-xs font-medium text-amber-600 hover:text-amber-700 flex items-center gap-1"><i data-lucide="pencil" class="w-3 h-3"></i> Edit</button>
                    <form method="POST" action="{{ route('admin.plans.destroy', $plan->id) }}" onsubmit="return confirm('हा प्लॅन हटवायचा?')">@csrf @method('DELETE')
                        <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700 flex items-center gap-1"><i data-lucide="trash-2" class="w-3 h-3"></i> हटवा</button>
                    </form>
                </div>

                {{-- Inline Edit Form --}}
                <div x-show="editPlan === {{ $plan->id }}" x-transition class="px-5 py-4 border-t border-amber-200 dark:border-amber-800 bg-amber-50/50 dark:bg-amber-900/10">
                    <form method="POST" action="{{ route('admin.plans.update', $plan->id) }}" class="space-y-3">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" name="name" value="{{ $plan->name }}" class="px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white" placeholder="नाव">
                            <select name="plan_type" class="px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white">
                                @foreach(['monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'half_yearly' => 'Half-Yearly', 'yearly' => 'Yearly'] as $k => $v)
                                <option value="{{ $k }}" {{ $plan->plan_type === $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="price" step="0.01" value="{{ $plan->price }}" class="px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white" placeholder="₹">
                            <input type="number" name="duration_days" value="{{ $plan->duration_days }}" class="px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white" placeholder="दिवस">
                        </div>
                        <input type="text" name="features" value="{{ implode(', ', $features) }}" class="w-full px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white" placeholder="Features (comma-separated)">
                        <div class="grid grid-cols-3 gap-2">
                            <input type="number" name="discount_percent" step="0.01" value="{{ $plan->discount_percent }}" class="px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white" placeholder="Discount %">
                            <input type="number" name="trial_days" value="{{ $plan->trial_days }}" class="px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white" placeholder="Trial days">
                            <input type="text" name="razorpay_plan_id" value="{{ $plan->razorpay_plan_id }}" class="px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white" placeholder="Razorpay ID">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition">Save</button>
                            <button type="button" @click="editPlan = null" class="px-4 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-lg transition">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
