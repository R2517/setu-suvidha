<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPaymentOrder extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'current_subscription_id',
        'action',
        'amount_paise',
        'status',
        'razorpay_order_id',
        'razorpay_payment_id',
    ];

    protected $casts = [
        'amount_paise' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function currentSubscription(): BelongsTo
    {
        return $this->belongsTo(VleSubscription::class, 'current_subscription_id');
    }
}
