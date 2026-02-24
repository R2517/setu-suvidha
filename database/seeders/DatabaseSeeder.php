<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $seeders = [
            FormPricingSeeder::class,
            SubscriptionPlanSeeder::class,
            BondFormatSeeder::class,
        ];

        $allowAdminSeeder = !app()->environment('production')
            || filter_var(env('SEED_ADMIN_USER', false), FILTER_VALIDATE_BOOL);

        if ($allowAdminSeeder) {
            $seeders[] = AdminUserSeeder::class;
        }

        $this->call($seeders);
    }
}
