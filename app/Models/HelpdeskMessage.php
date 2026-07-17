<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpdeskMessage extends Model
{
    protected $fillable = ['helpdesk_ticket_id', 'sender_type', 'sender_id', 'message', 'attachment_path'];

    public function ticket()
    {
        return $this->belongsTo(HelpdeskTicket::class, 'helpdesk_ticket_id');
    }

    public function sender()
    {
        if ($this->sender_type === 'user') {
            return $this->belongsTo(User::class, 'sender_id');
        }
        return null; // Admin could be a different model or handled differently
    }
}
