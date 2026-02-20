<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE vle_subscriptions MODIFY COLUMN status ENUM('active', 'trial', 'expired', 'cancelled') DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE vle_subscriptions MODIFY COLUMN status ENUM('active', 'expired', 'cancelled') DEFAULT 'active'");
    }
};
