<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BondFormat extends Model
{
    protected $fillable = [
        'slug',
        'title_en',
        'title_mr',
        'description_mr',
        'fee',
        'icon',
        'icon_bg_color',
        'is_active',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
