<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillingSale extends Model
{
    protected $fillable = [
        'user_id', 'customer_id', 'invoice_number', 'customer_name', 'customer_phone',
        'total_amount', 'discount_amount', 'received_amount', 'due_amount',
        'payment_mode', 'cash_amount', 'online_amount', 'payment_status',
        'remarks', 'sale_date', 'is_deleted', 'delete_reason', 'deleted_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'cash_amount' => 'decimal:2',
        'online_amount' => 'decimal:2',
        'sale_date' => 'date',
        'deleted_at' => 'datetime',
        'is_deleted' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(BillingCustomer::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BillingSaleItem::class, 'sale_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_deleted', false);
    }

    public function scopeDeleted($query)
    {
        return $query->where('is_deleted', true);
    }
}
