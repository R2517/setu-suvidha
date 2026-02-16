<?php

namespace App\Console\Commands;

use App\Models\BandkamRegistration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BandkamExpiryAlert extends Command
{
    protected $signature = 'bandkam:expiry-alert {--days=7 : Days before expiry to alert}';
    protected $description = 'Log and flag Bandkam registrations expiring within N days';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $threshold = Carbon::now()->addDays($days)->toDateString();

        $expiring = BandkamRegistration::where('status', 'activated')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', $threshold)
            ->where('expiry_date', '>=', now()->toDateString())
            ->with('user')
            ->get();

        $expired = BandkamRegistration::where('status', 'activated')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now()->toDateString())
            ->get();

        // Auto-mark expired registrations
        foreach ($expired as $reg) {
            $reg->update(['status' => 'expired']);
            Log::info("Bandkam #{$reg->id} ({$reg->applicant_name}) marked as expired.");
        }

        if ($expired->count() > 0) {
            $this->info("Marked {$expired->count()} registration(s) as expired.");
        }

        // Log expiring registrations
        foreach ($expiring as $reg) {
            $daysLeft = Carbon::now()->diffInDays($reg->expiry_date, false);
            Log::warning("Bandkam #{$reg->id} ({$reg->applicant_name}) expires in {$daysLeft} days on {$reg->expiry_date}. User: {$reg->user?->email}");
        }

        if ($expiring->count() > 0) {
            $this->info("Found {$expiring->count()} registration(s) expiring within {$days} days.");
        } else {
            $this->info("No registrations expiring within {$days} days.");
        }

        return self::SUCCESS;
    }
}
