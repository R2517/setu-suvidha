<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormSubmission extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'form_type',
        'applicant_name',
        'form_data',
        'print_count',
        'last_printed_at',
    ];

    protected $casts = [
        'form_data' => 'array',
        'last_printed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
