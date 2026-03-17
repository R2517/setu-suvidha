<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogRedirect extends Model
{
    protected $fillable = ['old_path', 'new_path', 'status_code'];

    protected $casts = [
        'status_code' => 'integer',
    ];
}
