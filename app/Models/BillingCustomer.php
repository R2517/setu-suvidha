<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillingCustomer extends Model
{
    protected $fillable = [
        'user_id', 'name', 'mobile', 'aadhaar_last_four', 'address', 'notes',
        'total_visits', 'total_spent', 'total_due',
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'total_due' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(BillingSale::class, 'customer_id');
    }
}
