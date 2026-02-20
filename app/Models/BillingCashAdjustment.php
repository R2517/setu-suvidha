<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingCashAdjustment extends Model
{
    protected $fillable = [
        'user_id', 'daily_book_id', 'type', 'amount', 'reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dailyBook(): BelongsTo
    {
        return $this->belongsTo(BillingDailyBook::class, 'daily_book_id');
    }
}
