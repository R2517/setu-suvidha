<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpdeskTicket extends Model
{
    protected $fillable = ['user_id', 'type', 'subject', 'message', 'attachment_path', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(HelpdeskMessage::class);
    }
}
