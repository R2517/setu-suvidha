<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@setusuvidha.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'is_active' => true,
            ]
        );

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

        // Test User
        $user = User::updateOrCreate(
            ['email' => 'user@setusuvidha.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('user123'),
                'is_active' => true,
            ]
        );

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => 'Test User',
                'email' => 'user@setusuvidha.com',
                'wallet_balance' => 500.00,
                'is_active' => true,
            ]
        );

        UserRole::updateOrCreate(
            ['user_id' => $user->id, 'role' => 'vle'],
            []
        );
    }
}
