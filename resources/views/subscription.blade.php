@extends('layouts.app')
@section('title', 'सब्सक्रिप्शन प्लॅन्स — SETU Suvidha')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 via-white to-gray-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 py-10 px-4" x-data="subscriptionPage()">

    {{-- Page Header --}}
    <div class="max-w-5xl mx-auto mb-10 text-center">
        <div class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 text-indigo-700 dark:text-indigo-300 px-4 py-1.5 rounded-full text-xs font-bold mb-4">
            <i data-lucide="crown" class="w-3.5 h-3.5"></i> सब्सक्रिप्शन
        </div>
        <h1 class="text-3xl lg:text-4xl font-black text-gray-900 dark:text-white mb-3">तुमच्यासाठी योग्य प्लॅन निवडा</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xl mx-auto">Billing, CRM आणि DocSlip वापरण्यासाठी ॲक्टिव्ह सब्सक्रिप्शन आवश्यक आहे</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="max-w-5xl mx-auto mb-6">
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3.5 rounded-2xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-5xl mx-auto mb-6">
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5"></i> {{ session('error') }}
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="max-w-5xl mx-auto mb-6">
        <div class="bg-amber-50 border border-amber-200 text-amber-700 px-5 py-3.5 rounded-2xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <i data-lucide="alert-triangle" class="w-5 h-5"></i> {{ session('warning') }}
        </div>
    </div>
    @endif

    {{-- Current Plan Card --}}
    @if($currentSub)
    <div class="max-w-5xl mx-auto mb-10">
        <div class="relative bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-lg overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r {{ $currentSub->status === 'trial' ? 'from-amber-400 to-orange-500' : 'from-green-400 to-emerald-500' }}"></div>
            <div class="p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl {{ $currentSub->status === 'trial' ? 'bg-amber-100 dark:bg-amber-900/30' : 'bg-green-100 dark:bg-green-900/30' }} flex items-center justify-center shrink-0">
                            <i data-lucide="{{ $currentSub->status === 'trial' ? 'clock' : 'shield-check' }}" class="w-6 h-6 {{ $currentSub->status === 'trial' ? 'text-amber-600' : 'text-green-600' }}"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider {{ $currentSub->status === 'trial' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $currentSub->status === 'trial' ? 'ट्रायल' : 'ॲक्टिव्ह' }}
                                </span>
                                <h2 class="text-lg font-black text-gray-900 dark:text-white">{{ $currentSub->plan->name ?? 'Current Plan' }}</h2>
                            </div>
                            <div class="flex flex-wrap gap-x-5 gap-y-1 text-xs text-gray-500 mt-1">
                                <span class="flex items-center gap-1"><i data-lucide="calendar" class="w-3 h-3"></i> सुरू: {{ $currentSub->start_date?->format('d M Y') }}</span>
                                @if($currentSub->end_date)
                                <span class="flex items-center gap-1"><i data-lucide="calendar-check" class="w-3 h-3"></i> शेवट: {{ $currentSub->end_date->format('d M Y') }}</span>
                                @endif
                                @if($currentSub->is_trial && $currentSub->trial_ends_at)
                                <span class="flex items-center gap-1 text-amber-600 font-bold">
                                    <i data-lucide="hourglass" class="w-3 h-3"></i>
                                    ट्रायल शेवट: {{ $currentSub->trial_ends_at->format('d M Y') }}
                                    ({{ max(0, (int) now()->startOfDay()->diffInDays($currentSub->trial_ends_at->copy()->startOfDay(), false)) }} दिवस)
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl px-5 py-3 text-center min-w-[140px]">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">वॉलेट शिल्लक</div>
                        <div class="text-2xl font-black text-gray-900 dark:text-white">₹{{ number_format($walletBalance, 2) }}</div>
                    </div>
                </div>

                @if($currentSub->is_trial && $currentSub->trial_ends_at && $currentSub->plan)
                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="bg-amber-50/80 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-800/30 flex items-center justify-center shrink-0">
                            <i data-lucide="info" class="w-4 h-4 text-amber-600"></i>
                        </div>
                        <p class="text-xs text-amber-700 dark:text-amber-300">ट्रायल संपल्यावर <strong>₹{{ number_format($currentSub->plan->price ?? 0, 2) }}</strong> वॉलेटमधून कापले जाईल</p>
                    </div>
                    <button type="button"
                            @click="openConfirm({{ (int)$currentSub->plan->id }}, '{{ addslashes($currentSub->plan->name ?? 'Plan') }}', {{ (float)($currentSub->plan->price ?? 0) }}, {{ (float)($currentSub->plan->maintenance_amount ?? 0) }}, {{ (int)($currentSub->plan->trial_days ?? 15) }}, 'activate_now')"
                            class="w-full py-3.5 rounded-2xl text-sm font-bold bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:opacity-90 transition shadow-lg shadow-green-500/20 flex items-center justify-center gap-2">
                        <i data-lucide="zap" class="w-4 h-4"></i> आत्ताच ॲक्टिव्हेट करा
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="max-w-5xl mx-auto mb-10">
        <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-violet-700 rounded-3xl p-8 text-white text-center overflow-hidden shadow-xl">
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.2) 0%, transparent 50%), radial-gradient(circle at 80% 50%, rgba(255,255,255,0.1) 0%, transparent 50%);"></div>
            <div class="relative">
                <div class="w-16 h-16 rounded-2xl bg-white/20 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="crown" class="w-8 h-8"></i>
                </div>
                <h2 class="text-2xl font-black mb-2">प्रीमियम प्लॅन निवडा</h2>
                <p class="text-sm text-white/70 mb-4 max-w-md mx-auto">ट्रायल सुरू करा आणि सर्व सेवांचा ॲक्सेस मिळवा</p>
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur px-5 py-2 rounded-full text-sm font-bold">
                    <i data-lucide="wallet" class="w-4 h-4"></i> वॉलेट शिल्लक: ₹{{ number_format($walletBalance, 2) }}
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Plan Cards --}}
    @php
        $planGradients = [
            'monthly' => ['from-blue-500 to-cyan-500', 'blue', 'zap'],
            'quarterly' => ['from-emerald-500 to-teal-500', 'emerald', 'trending-up'],
            'half_yearly' => ['from-purple-500 to-violet-500', 'purple', 'star'],
            'yearly' => ['from-amber-500 to-orange-500', 'amber', 'gem'],
        ];
        $planLabels = [
            'monthly' => 'मासिक',
            'quarterly' => 'त्रैमासिक',
            'half_yearly' => 'सहामाही',
            'yearly' => 'वार्षिक',
        ];
        // Reorder: put highest-price plan in center
        $sortedPlans = $plans->sortBy('price')->values();
        if ($sortedPlans->count() === 3) {
            $reorderedPlans = collect([$sortedPlans[0], $sortedPlans[2], $sortedPlans[1]]);
        } else {
            $reorderedPlans = $sortedPlans;
        }
    @endphp

    <div class="max-w-5xl mx-auto mb-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($reorderedPlans as $index => $plan)
            @php
                $isCurrentPlan = $currentSub && (int) $currentSub->plan_id === (int) $plan->id;
                $grad = $planGradients[$plan->plan_type] ?? $planGradients['monthly'];
                $label = $planLabels[$plan->plan_type] ?? str_replace('_', ' ', $plan->plan_type);
                $isHighlight = $reorderedPlans->count() >= 3 && $index === 1;
            @endphp
            <div class="group relative bg-white dark:bg-gray-900 rounded-3xl border-2 {{ $isCurrentPlan ? 'border-green-400 shadow-xl shadow-green-500/10' : ($isHighlight ? 'border-indigo-300 dark:border-indigo-700 shadow-xl shadow-indigo-500/10 md:scale-105' : 'border-gray-200 dark:border-gray-800 shadow-lg') }} overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">

                {{-- Current / Popular Badge --}}
                @if($isCurrentPlan)
                <div class="absolute top-0 left-1/2 -translate-x-1/2 z-10">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white text-[10px] font-black uppercase tracking-wider px-4 py-1 rounded-b-lg shadow-md">
                        ✅ सध्याचा प्लॅन
                    </div>
                </div>
                @elseif($isHighlight)
                <div class="absolute top-0 left-1/2 -translate-x-1/2 z-10">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-[10px] font-black uppercase tracking-wider px-4 py-1 rounded-b-lg shadow-md">
                        ⭐ सर्वात लोकप्रिय
                    </div>
                </div>
                @endif

                {{-- Discount Badge --}}
                @if($plan->discount_percent > 0)
                <div class="absolute top-4 right-4 z-10">
                    <div class="bg-red-500 text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow-md animate-pulse">
                        {{ (int)$plan->discount_percent }}% OFF
                    </div>
                </div>
                @endif

                {{-- Card Header --}}
                <div class="relative px-6 pt-10 pb-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $grad[0] }} flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i data-lucide="{{ $grad[2] }}" class="w-7 h-7 text-white"></i>
                    </div>
                    <div class="text-xs font-bold uppercase tracking-widest text-{{ $grad[1] }}-600 dark:text-{{ $grad[1] }}-400 mb-2">{{ $label }}</div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-3">{{ $plan->name }}</h3>
                    <div class="flex items-baseline justify-center gap-1">
                        <span class="text-4xl font-black text-gray-900 dark:text-white">₹{{ number_format($plan->price, 0) }}</span>
                        <span class="text-sm text-gray-400 font-medium">/ {{ $plan->duration_days }} दिवस</span>
                    </div>
                </div>

                <div class="mx-6 border-t border-gray-100 dark:border-gray-800"></div>

                {{-- Card Body --}}
                <div class="px-6 py-5 flex-1 flex flex-col">
                    @if($plan->maintenance_amount > 0)
                    <div class="flex items-center justify-between bg-amber-50/80 dark:bg-amber-900/20 rounded-xl px-4 py-2.5 mb-4 border border-amber-100 dark:border-amber-800/30">
                        <span class="text-xs font-semibold text-amber-700 dark:text-amber-300">मेंटेनन्स</span>
                        <span class="text-sm font-black text-amber-700 dark:text-amber-300">₹{{ number_format($plan->maintenance_amount, 0) }}</span>
                    </div>
                    @endif

                    @if($plan->trial_days > 0)
                    <div class="flex items-center gap-2 bg-green-50/80 dark:bg-green-900/20 rounded-xl px-4 py-2.5 mb-4 border border-green-100 dark:border-green-800/30">
                        <i data-lucide="gift" class="w-4 h-4 text-green-600 dark:text-green-400"></i>
                        <span class="text-xs font-bold text-green-700 dark:text-green-300">{{ $plan->trial_days }} दिवस मोफत ट्रायल</span>
                    </div>
                    @endif

                    @if(is_array($plan->features) && count($plan->features) > 0)
                    <ul class="space-y-2.5 mb-5 flex-1">
                        @foreach($plan->features as $feature)
                        <li class="flex items-start gap-2.5">
                            <div class="w-5 h-5 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="check" class="w-3 h-3 text-green-600 dark:text-green-400"></i>
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    <div class="mt-auto pt-3">
                        @if($isCurrentPlan)
                        <button disabled class="w-full py-3.5 rounded-2xl text-sm font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 cursor-not-allowed flex items-center justify-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> ॲक्टिव्ह आहे
                        </button>
                        @elseif($currentSub)
                        <button type="button"
                                @click="openConfirm({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ (float)$plan->price }}, {{ (float)($plan->maintenance_amount ?? 0) }}, {{ (int)($plan->trial_days ?? 15) }}, 'change')"
                                class="w-full py-3.5 rounded-2xl text-sm font-bold {{ $isHighlight ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg shadow-indigo-500/25' : 'bg-gradient-to-r ' . $grad[0] . ' shadow-md' }} text-white hover:opacity-95 transition-all duration-300 flex items-center justify-center gap-2">
                            <i data-lucide="repeat" class="w-4 h-4"></i> प्लॅन बदला
                        </button>
                        @else
                        <button type="button"
                                @click="openConfirm({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ (float)$plan->price }}, {{ (float)($plan->maintenance_amount ?? 0) }}, {{ (int)($plan->trial_days ?? 15) }}, 'activate')"
                                class="w-full py-3.5 rounded-2xl text-sm font-bold {{ $isHighlight ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg shadow-indigo-500/25' : 'bg-gradient-to-r ' . $grad[0] . ' shadow-md' }} text-white hover:opacity-95 transition-all duration-300 flex items-center justify-center gap-2">
                            <i data-lucide="rocket" class="w-4 h-4"></i> सुरुवात करा
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Subscription History --}}
    @if($history->count() > 0)
    <div class="max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-lg">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                    <i data-lucide="history" class="w-4 h-4 text-gray-500"></i>
                </div>
                <h3 class="text-sm font-black text-gray-900 dark:text-white">सब्सक्रिप्शन इतिहास</h3>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-800">
                @foreach($history as $sub)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                    <div class="flex items-center gap-3">
                        @php
                            $statusIcons = ['active' => 'check-circle', 'trial' => 'clock', 'expired' => 'x-circle', 'cancelled' => 'minus-circle'];
                            $statusColors = ['active' => 'text-green-500', 'trial' => 'text-amber-500', 'expired' => 'text-red-400', 'cancelled' => 'text-gray-400'];
                        @endphp
                        <i data-lucide="{{ $statusIcons[$sub->status] ?? 'circle' }}" class="w-4 h-4 {{ $statusColors[$sub->status] ?? 'text-gray-400' }}"></i>
                        <div>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $sub->plan->name ?? '-' }}</span>
                            <span class="text-xs text-gray-400 ml-2">{{ $sub->start_date?->format('d M Y') }}</span>
                        </div>
                    </div>
                    @php
                        $badgeStyles = [
                            'active' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'trial' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                            'expired' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                            'cancelled' => 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400',
                        ];
                        $statusLabels = ['active' => 'ॲक्टिव्ह', 'trial' => 'ट्रायल', 'expired' => 'एक्स्पायर्ड', 'cancelled' => 'रद्द'];
                    @endphp
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $badgeStyles[$sub->status] ?? 'bg-gray-100 text-gray-500' }}">
                        {{ $statusLabels[$sub->status] ?? ucfirst($sub->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div x-show="showConfirm" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display:none">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showConfirm=false"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-sm shadow-2xl border border-gray-200 dark:border-gray-800 overflow-hidden" @click.stop>
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 px-6 py-4 text-white">
                <h2 class="text-base font-black flex items-center gap-2"><i data-lucide="shield-check" class="w-5 h-5"></i> Payment Confirmation</h2>
            </div>
            <div class="p-5 space-y-3">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Plan</span>
                        <span class="font-bold text-gray-900 dark:text-white" x-text="confirmPlan"></span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Action</span>
                        <span class="font-bold text-gray-900 dark:text-white" x-text="actionLabel()"></span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Plan Price</span>
                        <span class="font-bold text-gray-900 dark:text-white" x-text="'Rs ' + confirmPrice.toFixed(2)"></span>
                    </div>
                    <div x-show="confirmAction === 'activate'" class="flex justify-between text-xs">
                        <span class="text-gray-500">Trial Period</span>
                        <span class="font-bold text-amber-600" x-text="confirmTrialDays + ' days'"></span>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-2 flex justify-between text-sm">
                        <span class="font-bold text-gray-700 dark:text-gray-300">Pay Now</span>
                        <span class="font-black text-red-600 text-base" x-text="'Rs ' + confirmDebit.toFixed(2)"></span>
                    </div>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-3 flex items-center justify-between">
                    <span class="text-xs font-bold text-blue-700">Wallet Balance</span>
                    <span class="text-sm font-black" :class="walletBalance >= confirmDebit ? 'text-green-600' : 'text-red-600'" x-text="'Rs ' + walletBalance.toFixed(2)"></span>
                </div>

                <template x-if="canSubmitWithWallet">
                    <div class="space-y-2 pt-1">
                        <form :action="formAction" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" :value="confirmPlanId">
                            <button type="submit" class="w-full py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold text-sm hover:opacity-90 transition flex items-center justify-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                <span x-text="submitButtonText()"></span>
                            </button>
                        </form>
                        <button @click="showConfirm=false" class="w-full py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl text-sm font-medium transition">Cancel</button>
                    </div>
                </template>

                <template x-if="!canSubmitWithWallet">
                    <div class="space-y-2 pt-1">
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-3 text-center">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 mx-auto mb-1"></i>
                            <p class="text-xs font-bold text-red-700">Insufficient wallet balance</p>
                            <p class="text-[11px] text-red-600">Required: Rs <span x-text="confirmDebit.toFixed(2)"></span> | Available: Rs <span x-text="walletBalance.toFixed(2)"></span></p>
                        </div>
                        <a href="{{ route('wallet') }}" class="block w-full py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-bold text-sm text-center hover:opacity-90 transition">
                            <i data-lucide="wallet" class="w-4 h-4 inline mr-1"></i> Recharge Wallet
                        </a>
                        <button type="button" @click="payOnline()" :disabled="gatewayLoading"
                                class="w-full py-3 bg-gradient-to-r from-purple-500 to-fuchsia-600 text-white rounded-xl font-bold text-sm hover:opacity-90 transition disabled:opacity-50">
                            <span x-show="!gatewayLoading">Pay Online (UPI/Card)</span>
                            <span x-show="gatewayLoading">Opening payment...</span>
                        </button>
                        <button @click="showConfirm=false" class="w-full py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl text-sm font-medium transition">Cancel</button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function subscriptionPage() {
    return {
        showConfirm: false,
        confirmPlanId: null,
        confirmPlan: '',
        confirmPrice: 0,
        confirmMaint: 0,
        confirmTrialDays: 0,
        confirmDebit: 0,
        confirmAction: 'activate',
        formAction: '{{ route('subscription.activate') }}',
        canSubmitWithWallet: true,
        gatewayLoading: false,
        walletBalance: {{ (float) $walletBalance }},
        isCurrentTrial: @json((bool) ($currentSub && $currentSub->is_trial && $currentSub->trial_ends_at && $currentSub->trial_ends_at->isFuture())),

        openConfirm(planId, planName, price, maint, trialDays, action) {
            this.confirmPlanId = planId;
            this.confirmPlan = planName;
            this.confirmPrice = Number(price || 0);
            this.confirmMaint = Number(maint || 0);
            this.confirmTrialDays = Number(trialDays || 0);
            this.confirmAction = action;
            this.gatewayLoading = false;

            if (action === 'activate') {
                this.formAction = '{{ route('subscription.activate') }}';
                this.confirmDebit = this.confirmMaint;
            } else if (action === 'change') {
                this.formAction = '{{ route('subscription.change') }}';
                this.confirmDebit = this.isCurrentTrial ? 0 : this.confirmPrice;
            } else {
                this.formAction = '{{ route('subscription.activate-now') }}';
                this.confirmDebit = this.confirmPrice;
            }

            this.canSubmitWithWallet = this.confirmDebit <= 0 || this.walletBalance >= this.confirmDebit;
            this.showConfirm = true;
        },

        actionLabel() {
            if (this.confirmAction === 'activate') return 'Activate Trial';
            if (this.confirmAction === 'change') return 'Change Plan';
            return 'Activate Now';
        },

        submitButtonText() {
            if (this.confirmDebit <= 0) {
                return this.confirmAction === 'change' ? 'Change plan now' : 'Continue';
            }
            return 'Pay Rs ' + this.confirmDebit.toFixed(2) + ' from wallet';
        },

        async payOnline() {
            if (this.gatewayLoading || this.confirmDebit <= 0) {
                return;
            }

            this.gatewayLoading = true;

            try {
                const orderRes = await fetch('{{ route('subscription.payment-order') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        plan_id: this.confirmPlanId,
                        action: this.confirmAction,
                    }),
                });

                const orderData = await orderRes.json();
                if (!orderRes.ok || !orderData.success) {
                    alert(orderData.message || 'Unable to create payment order.');
                    this.gatewayLoading = false;
                    return;
                }

                const options = {
                    key: orderData.key_id,
                    amount: orderData.amount,
                    currency: 'INR',
                    name: 'SETU Suvidha',
                    description: this.actionLabel() + ' - ' + this.confirmPlan,
                    order_id: orderData.order_id,
                    handler: async (response) => {
                        try {
                            const verifyRes = await fetch('{{ route('subscription.payment-verify') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    razorpay_order_id: response.razorpay_order_id,
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_signature: response.razorpay_signature,
                                }),
                            });

                            const verifyData = await verifyRes.json();
                            if (verifyRes.ok && verifyData.success) {
                                window.location.href = verifyData.redirect || '{{ route('subscription') }}';
                                return;
                            }

                            alert(verifyData.message || 'Payment verification failed.');
                        } catch (e) {
                            alert('Payment verification failed. Please retry.');
                        } finally {
                            this.gatewayLoading = false;
                        }
                    },
                    prefill: {
                        name: '{{ addslashes(auth()->user()->name ?? '') }}',
                        email: '{{ addslashes(auth()->user()->email ?? '') }}',
                    },
                    theme: { color: '#7c3aed' },
                    modal: {
                        ondismiss: () => {
                            this.gatewayLoading = false;
                        },
                    },
                };

                const rzp = new Razorpay(options);
                rzp.open();
            } catch (e) {
                alert('Unable to start online payment. Please try again.');
                this.gatewayLoading = false;
            }
        },
    };
}
</script>
@endpush
