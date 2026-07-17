<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class BandkamRegistration extends Model
{
    use SoftDeletes;
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'applicant_name', 'mobile_number', 'aadhar_number', 'dob',
        'district', 'taluka', 'village', 'registration_type', 'application_number',
        'status', 'form_date', 'appointment_date', 'activation_date', 'expiry_date',
        'online_date', 'amount', 'received_amount', 'payment_status', 'payment_mode',
    ];

    protected $casts = [
        'dob' => 'date', 'form_date' => 'date', 'appointment_date' => 'date',
        'activation_date' => 'date', 'expiry_date' => 'date', 'online_date' => 'date',
        'amount' => 'decimal:2', 'received_amount' => 'decimal:2', 'created_at' => 'datetime',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schemes(): HasMany
    {
        return $this->hasMany(BandkamScheme::class, 'registration_id');
    }
}
