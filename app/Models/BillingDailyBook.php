<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillingDailyBook extends Model
{
    protected $fillable = [
        'user_id', 'business_date', 'opening_cash', 'closing_cash', 'expected_cash',
        'difference', 'status', 'close_version', 'closing_notes',
    ];

    protected $casts = [
        'business_date' => 'date',
        'opening_cash' => 'decimal:2',
        'closing_cash' => 'decimal:2',
        'expected_cash' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cashAdjustments(): HasMany
    {
        return $this->hasMany(BillingCashAdjustment::class, 'daily_book_id');
    }
}
