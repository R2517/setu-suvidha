@extends('layouts.app')
@section('title', 'सबस्क्रिप्शन प्लॅन')
@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8 px-4" x-data="subscriptionPage()">

    {{-- Header --}}
    <div class="max-w-5xl mx-auto mb-8 text-center">
        <h1 class="text-2xl font-black text-gray-900 dark:text-white mb-2">सबस्क्रिप्शन प्लॅन</h1>
        <p class="text-sm text-gray-500">बिलिंग, CRM आणि DocSlip वापरण्यासाठी सक्रिय प्लॅन आवश्यक आहे</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="max-w-5xl mx-auto mb-6">
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3.5 rounded-xl text-sm font-medium flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="max-w-5xl mx-auto mb-6">
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-xl text-sm font-medium flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i> {{ session('error') }}
        </div>
    </div>
    @endif
    @if(session('warning'))
    <div class="max-w-5xl mx-auto mb-6">
        <div class="bg-amber-50 border border-amber-200 text-amber-700 px-5 py-3.5 rounded-xl text-sm font-medium flex items-center gap-2">
            <i data-lucide="alert-triangle" class="w-5 h-5"></i> {{ session('warning') }}
        </div>
    </div>
    @endif

    {{-- Current Subscription Status --}}
    @if($currentSub)
    <div class="max-w-5xl mx-auto mb-8">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $currentSub->status === 'trial' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                            {{ $currentSub->status === 'trial' ? 'ट्रायल' : 'सक्रिय' }}
                        </span>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $currentSub->plan->name ?? 'प्लॅन' }}</h2>
                    </div>
                    <div class="flex flex-wrap gap-4 text-xs text-gray-500 mt-2">
                        <span><strong>सुरू:</strong> {{ $currentSub->start_date->format('d M Y') }}</span>
                        @if($currentSub->end_date)
                        <span><strong>समाप्त:</strong> {{ $currentSub->end_date->format('d M Y') }}</span>
                        @endif
                        @if($currentSub->is_trial && $currentSub->trial_ends_at)
                        <span class="text-amber-600 font-bold"><strong>ट्रायल समाप्त:</strong> {{ $currentSub->trial_ends_at->format('d M Y') }} ({{ $currentSub->trial_ends_at->diffInDays(now()) }} दिवस बाकी)</span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-500 mb-1">वॉलेट शिल्लक</div>
                    <div class="text-xl font-black text-gray-900 dark:text-white">₹{{ number_format($walletBalance, 2) }}</div>
                </div>
            </div>
            @if($currentSub->is_trial && $currentSub->trial_ends_at)
            <div class="mt-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-3 text-xs text-amber-700">
                <i data-lucide="info" class="w-3.5 h-3.5 inline mr-1"></i>
                ट्रायल कालावधी संपल्यानंतर ₹{{ number_format($currentSub->plan->price ?? 0, 2) }} तुमच्या वॉलेटमधून कापले जातील. पुरेशी शिल्लक ठेवा.
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="max-w-5xl mx-auto mb-8">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 text-white text-center">
            <div class="flex items-center justify-center gap-3 mb-2">
                <i data-lucide="crown" class="w-8 h-8"></i>
                <h2 class="text-xl font-black">प्लॅन निवडा आणि सुरू करा!</h2>
            </div>
            <p class="text-sm text-white/80 mb-3">15 दिवसांचा ट्रायल कालावधी — फक्त मेंटेनन्स शुल्क लागेल</p>
            <div class="text-sm font-bold bg-white/20 inline-block px-4 py-1.5 rounded-full">वॉलेट शिल्लक: ₹{{ number_format($walletBalance, 2) }}</div>
        </div>
    </div>
    @endif

    {{-- Plans Grid --}}
    <div class="max-w-5xl mx-auto mb-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($plans as $plan)
            @php
                $isCurrentPlan = $currentSub && $currentSub->plan_id == $plan->id;
                $planColors = [
                    'monthly' => ['from-blue-500', 'to-blue-600', 'border-blue-200', 'bg-blue-50', 'text-blue-700'],
                    'quarterly' => ['from-emerald-500', 'to-emerald-600', 'border-emerald-200', 'bg-emerald-50', 'text-emerald-700'],
                    'half_yearly' => ['from-purple-500', 'to-purple-600', 'border-purple-200', 'bg-purple-50', 'text-purple-700'],
                    'yearly' => ['from-amber-500', 'to-amber-600', 'border-amber-200', 'bg-amber-50', 'text-amber-700'],
                ];
                $colors = $planColors[$plan->plan_type] ?? $planColors['monthly'];
            @endphp
            <div class="bg-white dark:bg-gray-900 rounded-2xl border-2 {{ $isCurrentPlan ? 'border-green-400 ring-2 ring-green-200' : 'border-gray-200 dark:border-gray-800' }} overflow-hidden relative flex flex-col">
                @if($isCurrentPlan)
                <div class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700">सध्याचा</div>
                @endif
                @if($plan->discount_percent > 0)
                <div class="absolute top-3 left-3 text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600">{{ (int)$plan->discount_percent }}% OFF</div>
                @endif

                {{-- Plan Header --}}
                <div class="bg-gradient-to-br {{ $colors[0] }} {{ $colors[1] }} px-5 py-4 text-white">
                    <div class="text-xs font-bold uppercase tracking-wider opacity-80 mb-1">{{ str_replace('_', ' ', $plan->plan_type) }}</div>
                    <div class="text-2xl font-black">₹{{ number_format($plan->price, 0) }}</div>
                    <div class="text-xs opacity-80">/ {{ $plan->duration_days }} दिवस</div>
                </div>

                {{-- Plan Details --}}
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3">{{ $plan->name }}</h3>

                    @if($plan->maintenance_amount > 0)
                    <div class="flex items-center justify-between {{ $colors[3] }} rounded-lg px-3 py-2 mb-3">
                        <span class="text-[10px] font-bold {{ $colors[4] }}">ट्रायल मेंटेनन्स</span>
                        <span class="text-xs font-black {{ $colors[4] }}">₹{{ number_format($plan->maintenance_amount, 0) }}</span>
                    </div>
                    @endif

                    @if($plan->trial_days > 0)
                    <div class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                        <i data-lucide="clock" class="w-3 h-3"></i> {{ $plan->trial_days }} दिवस ट्रायल
                    </div>
                    @endif

                    @if(is_array($plan->features) && count($plan->features) > 0)
                    <ul class="space-y-1.5 mb-4 flex-1">
                        @foreach($plan->features as $feature)
                        <li class="text-xs text-gray-600 dark:text-gray-400 flex items-start gap-1.5">
                            <i data-lucide="check" class="w-3 h-3 text-green-500 mt-0.5 shrink-0"></i>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    {{-- Action Button --}}
                    <div class="mt-auto pt-3">
                        @if($isCurrentPlan)
                        <button disabled class="w-full py-2.5 rounded-xl text-sm font-bold bg-green-100 text-green-700 cursor-not-allowed">
                            <i data-lucide="check-circle" class="w-4 h-4 inline mr-1"></i> सक्रिय
                        </button>
                        @elseif($currentSub)
                        <button type="button" @click="openConfirm({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ (float)$plan->price }}, {{ (float)($plan->maintenance_amount ?? 0) }}, {{ $plan->trial_days ?? 15 }}, 'change')" class="w-full py-2.5 rounded-xl text-sm font-bold bg-gradient-to-r {{ $colors[0] }} {{ $colors[1] }} text-white hover:opacity-90 transition">
                            प्लॅन बदला
                        </button>
                        @else
                        <button type="button" @click="openConfirm({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ (float)$plan->price }}, {{ (float)($plan->maintenance_amount ?? 0) }}, {{ $plan->trial_days ?? 15 }}, 'activate')" class="w-full py-2.5 rounded-xl text-sm font-bold bg-gradient-to-r {{ $colors[0] }} {{ $colors[1] }} text-white hover:opacity-90 transition">
                            सक्रिय करा
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- How it works --}}
    <div class="max-w-5xl mx-auto mb-10">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="help-circle" class="w-4 h-4 text-indigo-500"></i> सबस्क्रिप्शन कसे कार्य करते?
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 text-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-lg font-black text-blue-600">1</span>
                    </div>
                    <h4 class="text-xs font-bold text-gray-900 dark:text-white mb-1">प्लॅन निवडा</h4>
                    <p class="text-[11px] text-gray-500">तुमच्या गरजेनुसार प्लॅन निवडा. मेंटेनन्स शुल्क वॉलेटमधून कापले जाईल.</p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-4 text-center">
                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-lg font-black text-amber-600">2</span>
                    </div>
                    <h4 class="text-xs font-bold text-gray-900 dark:text-white mb-1">15 दिवस ट्रायल</h4>
                    <p class="text-[11px] text-gray-500">ट्रायल कालावधीत बिलिंग, CRM, DocSlip सर्व वैशिष्ट्ये वापरा.</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 text-center">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-lg font-black text-green-600">3</span>
                    </div>
                    <h4 class="text-xs font-bold text-gray-900 dark:text-white mb-1">ऑटो-अ‍ॅक्टिव्हेट</h4>
                    <p class="text-[11px] text-gray-500">ट्रायल नंतर पूर्ण रक्कम वॉलेटमधून कापली जाईल. शिल्लक ठेवा!</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Subscription History --}}
    @if($history->count() > 0)
    <div class="max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="history" class="w-4 h-4 text-gray-500"></i> सबस्क्रिप्शन इतिहास
                </h3>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-800">
                @foreach($history as $sub)
                <div class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $sub->plan->name ?? '—' }}</span>
                        <span class="text-[10px] text-gray-400 ml-2">{{ $sub->start_date->format('d M Y') }}</span>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                        {{ $sub->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $sub->status === 'trial' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $sub->status === 'expired' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $sub->status === 'cancelled' ? 'bg-gray-100 text-gray-500' : '' }}
                    ">{{ ucfirst($sub->status) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

{{-- ═══ CONFIRMATION MODAL ═══ --}}
<div x-show="showConfirm" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display:none">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showConfirm=false"></div>
    <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-sm shadow-2xl border border-gray-200 dark:border-gray-800 overflow-hidden" @click.stop>
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 px-6 py-4 text-white">
            <h2 class="text-base font-black flex items-center gap-2"><i data-lucide="shield-check" class="w-5 h-5"></i> पेमेंट पुष्टी</h2>
        </div>
        <div class="p-5 space-y-3">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">प्लॅन</span>
                    <span class="font-bold text-gray-900 dark:text-white" x-text="confirmPlan"></span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">प्लॅन किंमत</span>
                    <span class="font-bold text-gray-900 dark:text-white" x-text="'₹' + confirmPrice"></span>
                </div>
                <div x-show="confirmAction === 'activate'" class="flex justify-between text-xs">
                    <span class="text-gray-500">ट्रायल कालावधी</span>
                    <span class="font-bold text-amber-600" x-text="confirmTrialDays + ' दिवस'"></span>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 pt-2 flex justify-between text-sm">
                    <span class="font-bold text-gray-700 dark:text-gray-300" x-text="confirmAction === 'activate' ? 'आता कापले जाईल (मेंटेनन्स):' : 'आता कापले जाईल:'"></span>
                    <span class="font-black text-red-600 text-base" x-text="'₹' + confirmDebit"></span>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-3 flex items-center justify-between">
                <span class="text-xs font-bold text-blue-700">वॉलेट शिल्लक</span>
                <span class="text-sm font-black" :class="walletBalance >= confirmDebit ? 'text-green-600' : 'text-red-600'" x-text="'₹' + walletBalance.toFixed(2)"></span>
            </div>

            <template x-if="confirmAction === 'activate' && confirmMaint > 0">
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-2.5 text-[11px] text-amber-700 flex items-start gap-1.5">
                    <i data-lucide="info" class="w-3.5 h-3.5 mt-0.5 shrink-0"></i>
                    <span>ट्रायल कालावधीत फक्त ₹<span x-text="confirmMaint"></span> मेंटेनन्स कापले जाईल. ट्रायल संपल्यानंतर ₹<span x-text="confirmPrice"></span> कापले जातील.</span>
                </div>
            </template>

            <template x-if="walletBalance >= confirmDebit">
                <div class="space-y-2 pt-1">
                    <form :action="confirmAction === 'activate' ? '{{ route('subscription.activate') }}' : '{{ route('subscription.change') }}'" method="POST" x-ref="confirmForm">
                        @csrf
                        <input type="hidden" name="plan_id" :value="confirmPlanId">
                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold text-sm hover:opacity-90 transition flex items-center justify-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            <span x-text="'₹' + confirmDebit + ' कापा आणि सक्रिय करा'"></span>
                        </button>
                    </form>
                    <button @click="showConfirm=false" class="w-full py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl text-sm font-medium transition">रद्द करा</button>
                </div>
            </template>

            <template x-if="walletBalance < confirmDebit">
                <div class="space-y-2 pt-1">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-3 text-center">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 mx-auto mb-1"></i>
                        <p class="text-xs font-bold text-red-700">अपुरी शिल्लक!</p>
                        <p class="text-[11px] text-red-600">आवश्यक: ₹<span x-text="confirmDebit"></span> | उपलब्ध: ₹<span x-text="walletBalance.toFixed(2)"></span></p>
                    </div>
                    <a href="{{ route('wallet') }}" class="block w-full py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-bold text-sm text-center hover:opacity-90 transition">
                        <i data-lucide="wallet" class="w-4 h-4 inline mr-1"></i> वॉलेट रिचार्ज करा
                    </a>
                    <button @click="showConfirm=false" class="w-full py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl text-sm font-medium transition">रद्द करा</button>
                </div>
            </template>
        </div>
    </div>
</div>
</div>

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
        walletBalance: {{ (float)$walletBalance }},

        openConfirm(planId, planName, price, maint, trialDays, action) {
            this.confirmPlanId = planId;
            this.confirmPlan = planName;
            this.confirmPrice = price;
            this.confirmMaint = maint;
            this.confirmTrialDays = trialDays;
            this.confirmAction = action;
            // During trial activation, only maintenance is charged. For plan change (not in trial), full price.
            this.confirmDebit = action === 'activate' ? maint : price;
            this.showConfirm = true;
        }
    };
}
</script>
@endsection
