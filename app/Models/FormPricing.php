<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormPricing extends Model
{
    protected $table = 'form_pricing';

    protected $fillable = [
        'form_type',
        'form_name',
        'price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
