<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\Request;
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
                'receipt' => 'wallet_' . $request->user()->id . '_' . time(),
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
            return response()->json(['success' => false, 'message' => 'वॉलेट ऑपरेशन अयशस्वी'], 500);
        }
    }
}
