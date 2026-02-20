<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingKioskTransaction extends Model
{
    protected $fillable = [
        'user_id', 'transaction_type', 'customer_name', 'customer_mobile',
        'aadhaar_last_four', 'bank_name', 'amount', 'manual_commission',
        'portal_commission', 'transaction_date', 'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'manual_commission' => 'decimal:2',
        'portal_commission' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
