<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocslipSavedRemark extends Model
{
    protected $fillable = ['user_id', 'text', 'sort_order'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
