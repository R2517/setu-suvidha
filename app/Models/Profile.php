<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'full_name_mr',
        'email',
        'shop_name',
        'shop_pic',
        'profile_pic',
        'shop_type',
        'business_categories',
        'mobile',
        'whatsapp_same',
        'whatsapp_number',
        'address',
        'state',
        'district',
        'taluka',
        'village',
        'promo_code',
        'gst_number',
        'csc_id',
        'setu_id',
        'logo_url',
        'working_hours',
        'kiosk_enabled',
        'kiosk_rates',
        'holiday_mode',
        'bank_name',
        'account_number',
        'ifsc_code',
        'upi_id',
        'qr_code_pic',
        'about_center',
        'google_map_link',
        'latitude',
        'longitude',
        'wallet_balance',
        'is_active',
        'is_public_approved',
        'dashboard_config',
    ];

    protected $casts = [
        'wallet_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'is_public_approved' => 'boolean',
        'whatsapp_same' => 'boolean',
        'kiosk_enabled' => 'boolean',
        'holiday_mode' => 'boolean',
        'dashboard_config' => 'array',
        'business_categories' => 'array',
        'working_hours' => 'array',
        'kiosk_rates' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
