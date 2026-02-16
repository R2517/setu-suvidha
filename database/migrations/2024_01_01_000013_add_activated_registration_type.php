<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE bandkam_registrations MODIFY COLUMN registration_type ENUM('new','renewal','activated') DEFAULT 'new'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE bandkam_registrations MODIFY COLUMN registration_type ENUM('new','renewal') DEFAULT 'new'");
    }
};
