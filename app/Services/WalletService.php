<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\FormPricing;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function deduct($user, string $formType, ?string $submissionId = null): array
    {
        $pricing = FormPricing::where('form_type', $formType)->where('is_active', true)->first();

        if (!$pricing) {
            return ['deducted' => 0, 'balance_after' => $user->getWalletBalance()];
        }

        return DB::transaction(function () use ($user, $pricing, $submissionId) {
            $profile = Profile::where('user_id', $user->id)->lockForUpdate()->first();

            if ($profile->wallet_balance < $pricing->price) {
                throw new \Exception(
                    "शिल्लक अपुरी आहे. आवश्यक: ₹{$pricing->price}, उपलब्ध: ₹{$profile->wallet_balance}"
                );
            }

            $newBalance = $profile->wallet_balance - $pricing->price;
            $profile->update(['wallet_balance' => $newBalance]);

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $pricing->price,
                'balance_after' => $newBalance,
                'description' => "{$pricing->form_name} फॉर्म शुल्क",
                'reference_id' => $submissionId,
            ]);

            return ['deducted' => $pricing->price, 'balance_after' => $newBalance];
        });
    }

    public function credit($user, float $amount, string $description, ?string $referenceId = null): array
    {
        return DB::transaction(function () use ($user, $amount, $description, $referenceId) {
            $profile = Profile::where('user_id', $user->id)->lockForUpdate()->first();

            // Idempotency: avoid double-crediting the same payment reference.
            if ($referenceId) {
                $alreadyCredited = WalletTransaction::where('type', 'credit')
                    ->where('reference_id', $referenceId)
                    ->exists();

                if ($alreadyCredited) {
                    return [
                        'credited' => 0,
                        'balance_after' => (float) $profile->wallet_balance,
                        'already_credited' => true,
                    ];
                }
            }

            $newBalance = $profile->wallet_balance + $amount;
            $profile->update(['wallet_balance' => $newBalance]);

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_after' => $newBalance,
                'description' => $description,
                'reference_id' => $referenceId,
            ]);

            return [
                'credited' => $amount,
                'balance_after' => $newBalance,
                'already_credited' => false,
            ];
        });
    }

    public function getBalance($user): float
    {
        return (float) ($user->profile?->wallet_balance ?? 0);
    }
}
