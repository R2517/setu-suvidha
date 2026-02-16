<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE bandkam_registrations MODIFY COLUMN payment_mode ENUM('cash','online','upi','cheque') DEFAULT 'cash'");
        DB::statement("ALTER TABLE bandkam_schemes MODIFY COLUMN status ENUM('pending','applied','approved','received','delivered','rejected') DEFAULT 'pending'");
        DB::statement("ALTER TABLE bandkam_schemes MODIFY COLUMN payment_mode ENUM('cash','online','upi','cheque') DEFAULT 'cash'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE bandkam_registrations MODIFY COLUMN payment_mode ENUM('cash','online','upi') DEFAULT 'cash'");
        DB::statement("ALTER TABLE bandkam_schemes MODIFY COLUMN status ENUM('pending','applied','approved','delivered') DEFAULT 'applied'");
        DB::statement("ALTER TABLE bandkam_schemes MODIFY COLUMN payment_mode ENUM('cash','online','upi') DEFAULT 'cash'");
    }
};
