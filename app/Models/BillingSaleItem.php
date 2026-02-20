<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingSaleItem extends Model
{
    protected $fillable = [
        'sale_id', 'service_name', 'quantity', 'unit_price', 'cost_price',
        'total_price', 'discount_amount',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(BillingSale::class, 'sale_id');
    }
}
