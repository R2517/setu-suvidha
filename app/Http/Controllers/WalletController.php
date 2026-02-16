<?php

namespace App\Http\Controllers;

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
            'amount' => 'required|numeric|min:1|max:50000',
        ]);

        $amount = (int) ($request->amount * 100); // Convert to paise

        try {
            $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));

            $order = $api->order->create([
                'receipt' => 'w_' . Str::uuid()->toString(),
                'amount' => $amount,
                'currency' => 'INR',
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order['id'],
                'key_id' => config('razorpay.key_id'),
                'amount' => $amount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Razorpay ऑर्डर तयार करता आली नाही'], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required',
            'amount' => 'required|numeric|min:1',
        ]);

        $expectedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
            config('razorpay.key_secret')
        );

        if ($expectedSignature !== $request->razorpay_signature) {
            return response()->json(['success' => false, 'message' => 'पेमेंट सत्यापन अयशस्वी'], 400);
        }

        try {
            $result = $this->walletService->credit(
                $request->user(),
                (float) $request->amount,
                'Razorpay वॉलेट रिचार्ज',
                $request->razorpay_payment_id
            );

            return response()->json([
                'success' => true,
                'message' => 'पेमेंट यशस्वी!',
                'balance' => $result['balance_after'],
            ]);
        } catch (\Exception $e) {
            Log::error('Wallet credit failed after payment verification', [
                'user_id' => $request->user()->id,
                'payment_id' => $request->razorpay_payment_id,
                'amount' => $request->amount,
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

        // Verify webhook signature
        $expectedSignature = hash_hmac('sha256', $request->getContent(), $webhookSecret);
        $receivedSignature = $request->header('X-Razorpay-Signature');

        if (!$receivedSignature || !hash_equals($expectedSignature, $receivedSignature)) {
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

            // Skip if already credited (idempotency)
            $existing = WalletTransaction::where('reference_id', $paymentId)->first();
            if ($existing) {
                return response()->json(['status' => 'already_processed']);
            }

            // Find user from notes or order
            $userId = $payment['notes']['user_id'] ?? null;
            if (!$userId) {
                Log::warning('Razorpay webhook: no user_id in payment notes', [
                    'payment_id' => $paymentId,
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
