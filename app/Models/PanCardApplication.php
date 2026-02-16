<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PanCardApplication extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'application_type',
        'status',
        'application_number',
        'applicant_name',
        'aadhar_number',
        'dob',
        'mobile_number',
        'amount',
        'received_amount',
        'payment_status',
        'payment_mode',
    ];

    protected $casts = [
        'dob' => 'date',
        'amount' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
