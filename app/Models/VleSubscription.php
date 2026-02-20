<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VleSubscription extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
        'status',
        'is_trial',
        'maintenance_paid',
        'plan_amount_paid',
        'razorpay_payment_id',
        'razorpay_subscription_id',
        'trial_ends_at',
        'auto_renew',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'trial_ends_at' => 'datetime',
        'auto_renew' => 'boolean',
        'is_trial' => 'boolean',
        'maintenance_paid' => 'boolean',
        'plan_amount_paid' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
}
