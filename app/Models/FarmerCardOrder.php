<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmerCardOrder extends Model
{
    protected $fillable = [
        'transaction_no', 'applicant_name', 'name_english', 'dob', 'gender',
        'mobile', 'aadhaar', 'farmer_id', 'address_village', 'address_taluka',
        'address_district', 'address_state', 'address_pincode', 'photo',
        'land_details', 'amount', 'razorpay_order_id', 'razorpay_payment_id',
        'status', 'download_count', 'last_downloaded_at', 'ip_address', 'data_purged',
    ];

    protected $casts = [
        'land_details' => 'array',
        'dob' => 'date',
        'last_downloaded_at' => 'datetime',
        'data_purged' => 'boolean',
    ];

    /**
     * Generate a unique transaction number like FIC-20260217-XXXXX
     */
    public static function generateTransactionNo(): string
    {
        do {
            $txn = 'FIC-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        } while (self::where('transaction_no', $txn)->exists());

        return $txn;
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
