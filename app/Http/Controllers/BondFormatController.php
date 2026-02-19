<?php

namespace App\Http\Controllers;

use App\Models\BondFormat;
use App\Models\Profile;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BondFormatController extends Controller
{
    public function index()
    {
        $formats = BondFormat::where('is_active', true)->get();
        return view('bonds.index', compact('formats'));
    }

    public function show(string $slug)
    {
        $format = BondFormat::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $user = auth()->user();
        $balance = $user->getWalletBalance();

        $viewName = 'bonds.' . str_replace('-', '_', $slug);

        if (!view()->exists($viewName)) {
            abort(404, 'Bond format view not found.');
        }

        return view($viewName, compact('format', 'balance'));
    }

    public function deductFee(Request $request)
    {
        $request->validate(['slug' => 'required|string']);

        $user = $request->user();
        $format = BondFormat::where('slug', $request->slug)->firstOrFail();
        $cost = (float) $format->fee;

        try {
            return DB::transaction(function () use ($user, $cost, $format) {
                $profile = Profile::where('user_id', $user->id)->lockForUpdate()->first();

                if (!$profile || $profile->wallet_balance < $cost) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => "शिल्लक अपुरी आहे. आवश्यक: ₹{$cost}, उपलब्ध: ₹" . ($profile->wallet_balance ?? 0),
                    ], 400);
                }

                $newBalance = $profile->wallet_balance - $cost;
                $profile->update(['wallet_balance' => $newBalance]);

                WalletTransaction::create([
                    'user_id'       => $user->id,
                    'type'          => 'debit',
                    'amount'        => $cost,
                    'balance_after' => $newBalance,
                    'description'   => 'Bond Format: ' . $format->title_en,
                    'reference_id'  => 'BOND-' . now()->format('YmdHis') . '-' . $user->id,
                ]);

                Log::info('Bond format payment', [
                    'user_id' => $user->id,
                    'slug'    => $format->slug,
                    'cost'    => $cost,
                    'balance' => $newBalance,
                ]);

                return response()->json([
                    'status'      => 'success',
                    'message'     => 'Payment successful.',
                    'new_balance' => number_format($newBalance, 2),
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Bond payment failed', [
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
