<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VleSavedCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'card_type', 
        'front_image_path', 
        'back_image_path', 
        'expires_at'
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
