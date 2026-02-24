<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletRechargeOrder extends Model
{
    protected $fillable = [
        'user_id',
        'razorpay_order_id',
        'amount_paise',
        'status',
        'razorpay_payment_id',
    ];

    protected $casts = [
        'amount_paise' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
