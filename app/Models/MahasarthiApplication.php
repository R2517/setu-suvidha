<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class MahasarthiApplication extends Model
{
    use SoftDeletes;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'applicant_name',
        'aadhar_number',
        'mobile_number',
        'application_number',
        'status',
        'applied_date',
        'card_otp_date',
        'ready_card_date',
        'delivered_date',
        'amount',
        'received_amount',
        'payment_status',
        'payment_mode',
        'print_count',
        'last_printed_at',
        'notes',
    ];

    protected $casts = [
        'applied_date' => 'date',
        'card_otp_date' => 'date',
        'ready_card_date' => 'date',
        'delivered_date' => 'date',
        'amount' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'last_printed_at' => 'datetime',
    ];

    // Prevent accidental Aadhaar exposure in JSON/API responses
    protected $hidden = ['aadhar_number'];

    public function setAadharNumberAttribute($value)
    {
        $this->attributes['aadhar_number'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getAadharNumberAttribute($value)
    {
        if (empty($value)) return null;
        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            return $value; // Legacy plaintext record
        }
    }

    /**
     * Map status to its corresponding date column.
     */
    public static function statusDateColumn(string $status): ?string
    {
        return match ($status) {
            'applied' => 'applied_date',
            'card_otp' => 'card_otp_date',
            'ready_card' => 'ready_card_date',
            'delivered' => 'delivered_date',
            default => null,
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
