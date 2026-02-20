<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingCommissionSlab extends Model
{
    protected $fillable = [
        'user_id', 'amount_from', 'amount_to', 'commission_percent',
        'balance_enquiry_rate', 'mini_statement_rate',
    ];

    protected $casts = [
        'amount_from' => 'decimal:2',
        'amount_to' => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'balance_enquiry_rate' => 'decimal:2',
        'mini_statement_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
