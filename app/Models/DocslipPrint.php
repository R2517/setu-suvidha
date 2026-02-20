<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocslipPrint extends Model
{
    protected $fillable = ['user_id', 'customer_name', 'customer_mobile', 'services_selected', 'documents_merged', 'remark'];

    protected $casts = [
        'services_selected' => 'array',
        'documents_merged'  => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
