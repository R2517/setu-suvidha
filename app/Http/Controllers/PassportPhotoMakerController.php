<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PassportPhotoMakerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $walletBalance = $user->getWalletBalance();

        return view('passport-photo-maker', compact('walletBalance', 'user'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'layout_type' => 'required|in:12,8,48,1',
        ]);

        $layoutType = $request->layout_type;
        $cost = $layoutType === '48' ? 8.00 : 2.00;
        $user = $request->user();

        try {
            return DB::transaction(function () use ($user, $cost, $layoutType) {
                $profile = Profile::where('user_id', $user->id)->lockForUpdate()->first();

                if (!$profile || $profile->wallet_balance < $cost) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "शिल्लक अपुरी आहे. आवश्यक: ₹{$cost}, उपलब्ध: ₹" . ($profile->wallet_balance ?? 0),
                    ], 400);
                }

                $newBalance = $profile->wallet_balance - $cost;
                $profile->update(['wallet_balance' => $newBalance]);

                $layoutLabels = [
                    '12' => '12 Photos (4×6)',
                    '8'  => '8 Photos (Bharti)',
                    '48' => '48 Photos (A4)',
                    '1'  => '1 Photo (Full)',
                ];

                WalletTransaction::create([
                    'user_id'      => $user->id,
                    'type'         => 'debit',
                    'amount'       => $cost,
                    'balance_after' => $newBalance,
                    'description'  => 'Passport Photo Print - ' . ($layoutLabels[$layoutType] ?? $layoutType),
                    'reference_id' => 'PPM-' . now()->format('YmdHis') . '-' . $user->id,
                ]);

                Log::info('PassportPro payment', [
                    'user_id' => $user->id,
                    'layout'  => $layoutType,
                    'cost'    => $cost,
                    'balance' => $newBalance,
                ]);

                return response()->json([
                    'status'      => 'success',
                    'new_balance' => $newBalance,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('PassportPro payment failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'पेमेंट प्रक्रिया अयशस्वी. कृपया पुन्हा प्रयत्न करा.',
            ], 500);
        }
    }
}
