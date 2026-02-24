@extends('layouts.app')
@section('title', 'Subscription Plans')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8 px-4" x-data="subscriptionPage()">
    <div class="max-w-5xl mx-auto mb-8 text-center">
        <h1 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Subscription Plans</h1>
        <p class="text-sm text-gray-500">Billing, CRM and DocSlip need an active subscription.</p>
    </div>

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

    @if($currentSub)
    <div class="max-w-5xl mx-auto mb-8">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $currentSub->status === 'trial' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                            {{ strtoupper($currentSub->status) }}
                        </span>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $currentSub->plan->name ?? 'Current Plan' }}</h2>
                    </div>
                    <div class="flex flex-wrap gap-4 text-xs text-gray-500 mt-2">
                        <span><strong>Start:</strong> {{ $currentSub->start_date?->format('d M Y') }}</span>
                        @if($currentSub->end_date)
                        <span><strong>End:</strong> {{ $currentSub->end_date->format('d M Y') }}</span>
                        @endif
                        @if($currentSub->is_trial && $currentSub->trial_ends_at)
                        <span class="text-amber-600 font-bold">
                            <strong>Trial Ends:</strong> {{ $currentSub->trial_ends_at->format('d M Y') }}
                            ({{ max(0, (int) now()->startOfDay()->diffInDays($currentSub->trial_ends_at->copy()->startOfDay(), false)) }} days)
                        </span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-500 mb-1">Wallet Balance</div>
                    <div class="text-xl font-black text-gray-900 dark:text-white">Rs {{ number_format($walletBalance, 2) }}</div>
                </div>
            </div>

            @if($currentSub->is_trial && $currentSub->trial_ends_at && $currentSub->plan)
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-3 text-xs text-amber-700">
                    Trial will convert to paid plan at <strong>Rs {{ number_format($currentSub->plan->price ?? 0, 2) }}</strong>.
                </div>
                <button type="button"
                        @click="openConfirm({{ (int)$currentSub->plan->id }}, '{{ addslashes($currentSub->plan->name ?? 'Plan') }}', {{ (float)($currentSub->plan->price ?? 0) }}, {{ (float)($currentSub->plan->maintenance_amount ?? 0) }}, {{ (int)($currentSub->plan->trial_days ?? 15) }}, 'activate_now')"
                        class="w-full py-2.5 rounded-xl text-sm font-bold bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:opacity-90 transition">
                    Activate Now
                </button>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="max-w-5xl mx-auto mb-8">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 text-white text-center">
            <div class="flex items-center justify-center gap-3 mb-2">
                <i data-lucide="crown" class="w-8 h-8"></i>
                <h2 class="text-xl font-black">Choose a Plan</h2>
            </div>
            <p class="text-sm text-white/80 mb-3">Start your trial and keep wallet or online payment ready for activation.</p>
            <div class="text-sm font-bold bg-white/20 inline-block px-4 py-1.5 rounded-full">Wallet: Rs {{ number_format($walletBalance, 2) }}</div>
        </div>
    </div>
    @endif

    <div class="max-w-5xl mx-auto mb-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($plans as $plan)
            @php
                $isCurrentPlan = $currentSub && (int) $currentSub->plan_id === (int) $plan->id;
                $planColors = [
                    'monthly' => ['from-blue-500', 'to-blue-600'],
                    'quarterly' => ['from-emerald-500', 'to-emerald-600'],
                    'half_yearly' => ['from-purple-500', 'to-purple-600'],
                    'yearly' => ['from-amber-500', 'to-amber-600'],
                ];
                $colors = $planColors[$plan->plan_type] ?? $planColors['monthly'];
            @endphp
            <div class="bg-white dark:bg-gray-900 rounded-2xl border-2 {{ $isCurrentPlan ? 'border-green-400 ring-2 ring-green-200' : 'border-gray-200 dark:border-gray-800' }} overflow-hidden relative flex flex-col">
                @if($isCurrentPlan)
                <div class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Current</div>
                @endif
                @if($plan->discount_percent > 0)
                <div class="absolute top-3 left-3 text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600">{{ (int)$plan->discount_percent }}% OFF</div>
                @endif

                <div class="bg-gradient-to-br {{ $colors[0] }} {{ $colors[1] }} px-5 py-4 text-white">
                    <div class="text-xs font-bold uppercase tracking-wider opacity-80 mb-1">{{ str_replace('_', ' ', $plan->plan_type) }}</div>
                    <div class="text-2xl font-black">Rs {{ number_format($plan->price, 0) }}</div>
                    <div class="text-xs opacity-80">/ {{ $plan->duration_days }} days</div>
                </div>

                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3">{{ $plan->name }}</h3>

                    @if($plan->maintenance_amount > 0)
                    <div class="flex items-center justify-between bg-amber-50 rounded-lg px-3 py-2 mb-3">
                        <span class="text-[10px] font-bold text-amber-700">Maintenance</span>
                        <span class="text-xs font-black text-amber-700">Rs {{ number_format($plan->maintenance_amount, 0) }}</span>
                    </div>
                    @endif

                    @if($plan->trial_days > 0)
                    <div class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                        <i data-lucide="clock" class="w-3 h-3"></i> {{ $plan->trial_days }} days trial
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

                    <div class="mt-auto pt-3">
                        @if($isCurrentPlan)
                        <button disabled class="w-full py-2.5 rounded-xl text-sm font-bold bg-green-100 text-green-700 cursor-not-allowed">
                            <i data-lucide="check-circle" class="w-4 h-4 inline mr-1"></i> Active
                        </button>
                        @elseif($currentSub)
                        <button type="button"
                                @click="openConfirm({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ (float)$plan->price }}, {{ (float)($plan->maintenance_amount ?? 0) }}, {{ (int)($plan->trial_days ?? 15) }}, 'change')"
                                class="w-full py-2.5 rounded-xl text-sm font-bold bg-gradient-to-r {{ $colors[0] }} {{ $colors[1] }} text-white hover:opacity-90 transition">
                            Change Plan
                        </button>
                        @else
                        <button type="button"
                                @click="openConfirm({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ (float)$plan->price }}, {{ (float)($plan->maintenance_amount ?? 0) }}, {{ (int)($plan->trial_days ?? 15) }}, 'activate')"
                                class="w-full py-2.5 rounded-xl text-sm font-bold bg-gradient-to-r {{ $colors[0] }} {{ $colors[1] }} text-white hover:opacity-90 transition">
                            Activate
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @if($history->count() > 0)
    <div class="max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="history" class="w-4 h-4 text-gray-500"></i> Subscription History
                </h3>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-800">
                @foreach($history as $sub)
                <div class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $sub->plan->name ?? '-' }}</span>
                        <span class="text-[10px] text-gray-400 ml-2">{{ $sub->start_date?->format('d M Y') }}</span>
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
