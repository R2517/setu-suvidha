<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $configuredPassword = env('ADMIN_DEFAULT_PASSWORD');
        $generatedPassword = null;

        if (!$configuredPassword) {
            $generatedPassword = Str::random(24);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@setusuvidha.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make($configuredPassword ?: $generatedPassword),
                'is_active' => true,
            ]
        );

        if ($configuredPassword && !Hash::check($configuredPassword, $admin->password)) {
            $admin->update([
                'password' => Hash::make($configuredPassword),
                'is_active' => true,
            ]);
        }

        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'full_name' => 'Admin',
                'email' => 'admin@setusuvidha.com',
                'wallet_balance' => 1000.00,
                'is_active' => true,
            ]
        );

        UserRole::updateOrCreate(
            ['user_id' => $admin->id, 'role' => 'admin'],
            []
        );

        if ($generatedPassword && $admin->wasRecentlyCreated) {
            $this->command?->warn("Generated admin password for admin@setusuvidha.com: {$generatedPassword}");
            $this->command?->warn('Set ADMIN_DEFAULT_PASSWORD in .env before production usage.');
        }
    }
}
