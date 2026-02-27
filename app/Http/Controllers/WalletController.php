<?php

namespace App\Http\Controllers;

use App\Models\WalletRechargeOrder;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class WalletController extends Controller
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $walletBalance = $user->getWalletBalance();
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.wallet', compact('walletBalance', 'transactions', 'user'));
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50|max:50000',
        ]);

        $amountPaise = (int) round($request->amount * 100); // Convert to paise

        try {
            $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));

            $order = $api->order->create([
                'receipt' => 'w_' . Str::uuid()->toString(),
                'amount' => $amountPaise,
                'currency' => 'INR',
                'notes' => [
                    'type' => 'wallet_recharge',
                    'user_id' => (string) $request->user()->id,
                ],
            ]);

            WalletRechargeOrder::create([
                'user_id' => $request->user()->id,
                'razorpay_order_id' => $order['id'],
                'amount_paise' => $amountPaise,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order['id'],
                'key_id' => config('razorpay.key_id'),
                'amount' => $amountPaise,
            ]);
        } catch (\Exception $e) {
            Log::error('Wallet Razorpay order creation failed', [
                'user_id' => $request->user()->id,
                'amount' => $request->amount,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['success' => false, 'message' => 'Razorpay ऑर्डर तयार करता आली नाही'], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required',
        ]);

        $user = $request->user();
        $rechargeOrder = WalletRechargeOrder::where('user_id', $user->id)
            ->where('razorpay_order_id', $request->razorpay_order_id)
            ->first();

        if (!$rechargeOrder) {
            return response()->json(['success' => false, 'message' => 'ऑर्डर सापडला नाही'], 404);
        }

        if ($rechargeOrder->status === 'paid') {
            return response()->json([
                'success' => true,
                'message' => 'पेमेंट आधीच सत्यापित झाले आहे',
                'balance' => $user->fresh()->getWalletBalance(),
            ]);
        }

        $expectedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
            config('razorpay.key_secret')
        );

        if (!hash_equals($expectedSignature, $request->razorpay_signature)) {
            return response()->json(['success' => false, 'message' => 'पेमेंट सत्यापन अयशस्वी'], 400);
        }

        try {
            $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            if (($payment['order_id'] ?? null) !== $rechargeOrder->razorpay_order_id) {
                return response()->json(['success' => false, 'message' => 'ऑर्डर जुळत नाही'], 400);
            }

            if ((int) ($payment['amount'] ?? 0) !== (int) $rechargeOrder->amount_paise) {
                return response()->json(['success' => false, 'message' => 'पेमेंट रक्कम जुळत नाही'], 400);
            }

            $paymentStatus = strtolower((string) ($payment['status'] ?? ''));
            if ($paymentStatus !== 'captured') {
                return response()->json(['success' => false, 'message' => 'पेमेंट कॅप्चर झालेले नाही'], 400);
            }

            $result = $this->walletService->credit(
                $user,
                (float) ($rechargeOrder->amount_paise / 100),
                'Razorpay वॉलेट रिचार्ज',
                $request->razorpay_payment_id
            );

            $rechargeOrder->update([
                'status' => 'paid',
                'razorpay_payment_id' => $request->razorpay_payment_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'पेमेंट यशस्वी!',
                'balance' => $result['balance_after'],
            ]);
        } catch (\Exception $e) {
            Log::error('Wallet credit failed after payment verification', [
                'user_id' => $user->id,
                'payment_id' => $request->razorpay_payment_id,
                'order_id' => $request->razorpay_order_id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['success' => false, 'message' => 'वॉलेट ऑपरेशन अयशस्वी'], 500);
        }
    }

    /**
     * B2: Razorpay webhook handler for server-side payment confirmation.
     * This ensures payment is credited even if the client-side callback fails.
     */
    public function webhook(Request $request)
    {
        $webhookSecret = config('razorpay.webhook_secret');

        if (empty($webhookSecret)) {
            Log::critical('Razorpay webhook secret is not configured. Webhook processing blocked.');
            return response()->json(['status' => 'misconfigured'], 500);
        }

        $receivedSignature = $request->header('X-Razorpay-Signature');

        if (!$receivedSignature) {
            Log::warning('Razorpay webhook: missing signature header', [
                'ip' => $request->ip(),
            ]);
            return response()->json(['status' => 'missing_signature'], 400);
        }

        $expectedSignature = hash_hmac('sha256', $request->getContent(), $webhookSecret);

        if (!hash_equals($expectedSignature, $receivedSignature)) {
            Log::warning('Razorpay webhook: invalid signature', [
                'ip' => $request->ip(),
            ]);
            return response()->json(['status' => 'invalid_signature'], 400);
        }

        $payload = $request->all();
        $event = $payload['event'] ?? null;

        if ($event === 'payment.captured') {
            $payment = $payload['payload']['payment']['entity'] ?? null;
            if (!$payment) {
                return response()->json(['status' => 'no_payment_entity'], 400);
            }

            $paymentId = $payment['id'];
            $amountInRupees = $payment['amount'] / 100;
            $orderId = $payment['order_id'] ?? null;
            $rechargeOrder = $orderId
                ? WalletRechargeOrder::where('razorpay_order_id', $orderId)->first()
                : null;

            // Skip if already credited (idempotency)
            $existing = WalletTransaction::where('type', 'credit')
                ->where('reference_id', $paymentId)
                ->first();
            if ($existing) {
                if ($rechargeOrder && $rechargeOrder->status !== 'paid') {
                    $rechargeOrder->update([
                        'status' => 'paid',
                        'razorpay_payment_id' => $paymentId,
                    ]);
                }
                return response()->json(['status' => 'already_processed']);
            }

            if ($rechargeOrder && (int) $payment['amount'] !== (int) $rechargeOrder->amount_paise) {
                Log::warning('Razorpay webhook: amount mismatch', [
                    'payment_id' => $paymentId,
                    'order_id' => $orderId,
                    'payload_amount' => $payment['amount'],
                    'expected_amount' => $rechargeOrder->amount_paise,
                ]);
                return response()->json(['status' => 'amount_mismatch'], 400);
            }

            // Find user from notes, fallback to local recharge order.
            $userId = $payment['notes']['user_id'] ?? $rechargeOrder?->user_id;
            if (!$userId) {
                Log::warning('Razorpay webhook: no user mapping', [
                    'payment_id' => $paymentId,
                    'order_id' => $orderId,
                ]);
                return response()->json(['status' => 'no_user_id'], 400);
            }

            $user = \App\Models\User::find($userId);
            if (!$user) {
                return response()->json(['status' => 'user_not_found'], 404);
            }

            try {
                $this->walletService->credit(
                    $user,
                    $amountInRupees,
                    'Razorpay वॉलेट रिचार्ज (webhook)',
                    $paymentId
                );

                if ($rechargeOrder && $rechargeOrder->status !== 'paid') {
                    $rechargeOrder->update([
                        'status' => 'paid',
                        'razorpay_payment_id' => $paymentId,
                    ]);
                }

                Log::info('Razorpay webhook: payment credited', [
                    'user_id' => $userId,
                    'payment_id' => $paymentId,
                    'amount' => $amountInRupees,
                ]);
            } catch (\Exception $e) {
                Log::error('Razorpay webhook: credit failed', [
                    'user_id' => $userId,
                    'payment_id' => $paymentId,
                    'error' => $e->getMessage(),
                ]);
                return response()->json(['status' => 'credit_failed'], 500);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
