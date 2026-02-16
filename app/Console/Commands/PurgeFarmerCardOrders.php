<?php

namespace App\Console\Commands;

use App\Models\FarmerCardOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PurgeFarmerCardOrders extends Command
{
    protected $signature = 'farmer-cards:purge';
    protected $description = 'Purge personal data from farmer card orders older than 7 days';

    public function handle(): int
    {
        $cutoff = now()->subDays(7);

        $orders = FarmerCardOrder::where('status', 'paid')
            ->where('data_purged', false)
            ->where('created_at', '<', $cutoff)
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            // Delete photo file
            if ($order->photo && Storage::disk('public')->exists($order->photo)) {
                Storage::disk('public')->delete($order->photo);
            }

            // Purge personal data but keep record for admin
            $order->update([
                'aadhaar'         => null,
                'photo'           => null,
                'land_details'    => null,
                'address_pincode' => null,
                'data_purged'     => true,
            ]);

            $count++;
        }

        // Also delete pending (unpaid) orders older than 24 hours
        $pendingDeleted = FarmerCardOrder::where('status', 'pending')
            ->where('created_at', '<', now()->subHours(24))
            ->delete();

        $this->info("Purged {$count} paid orders. Deleted {$pendingDeleted} stale pending orders.");

        return self::SUCCESS;
    }
}
