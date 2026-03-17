<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogImage extends Model
{
    protected $fillable = [
        'user_id',
        'original_name',
        'file_path',
        'file_path_webp',
        'thumbnail_path',
        'alt_text',
        'caption',
        'width',
        'height',
        'file_size',
        'mime_type',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'file_size' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
