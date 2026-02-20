<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingService extends Model
{
    protected $fillable = [
        'user_id', 'name', 'name_mr', 'category', 'default_price', 'cost_price',
        'is_active', 'is_system_default', 'display_order', 'override_id',
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_system_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
