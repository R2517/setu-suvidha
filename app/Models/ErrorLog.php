<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErrorLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'level',
        'fingerprint',
        'occurrence_count',
        'last_seen_at',
        'message',
        'file',
        'line',
        'url',
        'method',
        'user_id',
        'ip',
        'user_agent',
        'trace',
        'context',
        'is_resolved',
    ];

    protected $casts = [
        'context' => 'array',
        'is_resolved' => 'boolean',
        'created_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateFingerprint(string $message, string $file, int $line): string
    {
        return hash('xxh3', $message . '|' . $file . '|' . $line);
    }
}
