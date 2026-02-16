<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\VillageInfo;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AadhaarController extends Controller
{
    public function hub(Request $request)
    {
        return view('aadhaar.hub');
    }

    public function adultForm(Request $request)
    {
        $addresses = VillageInfo::where('user_id', $request->user()->id)->get();
        $walletBalance = $request->user()->getWalletBalance();
        return view('aadhaar.adult-form', compact('addresses', 'walletBalance'));
    }

    public function minorForm(Request $request)
    {
        $addresses = VillageInfo::where('user_id', $request->user()->id)->get();
        $walletBalance = $request->user()->getWalletBalance();
        return view('aadhaar.minor-form', compact('addresses', 'walletBalance'));
    }

    public function childForm(Request $request)
    {
        $addresses = VillageInfo::where('user_id', $request->user()->id)->get();
        $walletBalance = $request->user()->getWalletBalance();
        return view('aadhaar.child-form', compact('addresses', 'walletBalance'));
    }

    public function updateForm(Request $request)
    {
        $addresses = VillageInfo::where('user_id', $request->user()->id)->get();
        $walletBalance = $request->user()->getWalletBalance();
        return view('aadhaar.update-form', compact('addresses', 'walletBalance'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'form_type' => 'required|in:adult,minor,child,update',
        ]);

        $user = $request->user();
        $cost = 5.00;

        try {
            return DB::transaction(function () use ($user, $cost, $request) {
                $profile = Profile::where('user_id', $user->id)->lockForUpdate()->first();

                if (!$profile || $profile->wallet_balance < $cost) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "शिल्लक अपुरी आहे. आवश्यक: ₹{$cost}, उपलब्ध: ₹" . ($profile->wallet_balance ?? 0),
                    ], 400);
                }

                $newBalance = $profile->wallet_balance - $cost;
                $profile->update(['wallet_balance' => $newBalance]);

                $formLabels = [
                    'adult'  => 'Adult Form (18+)',
                    'minor'  => 'Minor Form (5-18)',
                    'child'  => 'Child Form (0-5)',
                    'update' => 'Update/Address Form',
                ];

                WalletTransaction::create([
                    'user_id'       => $user->id,
                    'type'          => 'debit',
                    'amount'        => $cost,
                    'balance_after' => $newBalance,
                    'description'   => 'Aadhaar ' . ($formLabels[$request->form_type] ?? $request->form_type) . ' print',
                    'reference_id'  => 'AADH-' . now()->format('YmdHis') . '-' . $user->id,
                ]);

                Log::info('Aadhaar form payment', [
                    'user_id'   => $user->id,
                    'form_type' => $request->form_type,
                    'cost'      => $cost,
                    'balance'   => $newBalance,
                ]);

                return response()->json([
                    'status'      => 'success',
                    'new_balance' => $newBalance,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Aadhaar payment failed', [
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
