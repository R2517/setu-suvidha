<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BandkamScheme extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'registration_id', 'user_id', 'scheme_type', 'applicant_name',
        'beneficiary_name', 'student_name', 'scholarship_category', 'year',
        'status', 'apply_date', 'appointment_date', 'delivery_date',
        'amount', 'received_amount', 'commission_percent', 'commission_amount',
        'payment_status', 'payment_mode',
    ];

    protected $casts = [
        'apply_date' => 'date', 'appointment_date' => 'date', 'delivery_date' => 'date',
        'amount' => 'decimal:2', 'received_amount' => 'decimal:2',
        'commission_percent' => 'decimal:2', 'commission_amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(BandkamRegistration::class, 'registration_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
