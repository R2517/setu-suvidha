<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'shop_name',
        'shop_type',
        'mobile',
        'address',
        'district',
        'taluka',
        'gst_number',
        'csc_id',
        'logo_url',
        'working_hours',
        'kiosk_enabled',
        'kiosk_rates',
        'holiday_mode',
        'wallet_balance',
        'is_active',
        'dashboard_config',
    ];

    protected $casts = [
        'wallet_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'kiosk_enabled' => 'boolean',
        'holiday_mode' => 'boolean',
        'dashboard_config' => 'array',
        'working_hours' => 'array',
        'kiosk_rates' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
