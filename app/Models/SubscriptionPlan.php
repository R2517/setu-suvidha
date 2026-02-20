<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'plan_type',
        'price',
        'maintenance_amount',
        'duration_days',
        'features',
        'discount_percent',
        'trial_days',
        'razorpay_plan_id',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'maintenance_amount' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'trial_days' => 'integer',
    ];
}
