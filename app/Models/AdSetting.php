<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_name',
        'is_active',
        'type',
        'content',
        'target_url'
    ];
}
