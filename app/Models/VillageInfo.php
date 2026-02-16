<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillageInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pincode',
        'state',
        'district',
        'taluka',
        'post_office',
        'village',
        'verifier_name',
        'certifier_name',
        'certifier_designation',
        'certifier_contact',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
