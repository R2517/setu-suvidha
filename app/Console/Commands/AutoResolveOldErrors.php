<?php

namespace App\Console\Commands;

use App\Models\ErrorLog;
use Illuminate\Console\Command;

class AutoResolveOldErrors extends Command
{
    protected $signature = 'errors:auto-resolve';

    protected $description = 'Auto-resolve errors not seen in the last 24 hours';

    public function handle(): int
    {
        $threshold = now()->subHours(24);

        $resolved = ErrorLog::where('is_resolved', false)
            ->where(function ($query) use ($threshold) {
                $query->where('last_seen_at', '<', $threshold)
                    ->orWhere(function ($q) use ($threshold) {
                        $q->whereNull('last_seen_at')
                            ->where('created_at', '<', $threshold);
                    });
            })
            ->update(['is_resolved' => true]);

        $this->info("Auto-resolved {$resolved} old error(s) not seen in 24h");

        return self::SUCCESS;
    }
}
