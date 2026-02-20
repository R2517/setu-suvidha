<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('gst_number', 20)->nullable()->after('taluka');
            $table->string('csc_id', 50)->nullable()->after('gst_number');
            $table->string('logo_url')->nullable()->after('csc_id');
            $table->json('working_hours')->nullable()->after('logo_url');
            $table->boolean('kiosk_enabled')->default(false)->after('working_hours');
            $table->json('kiosk_rates')->nullable()->after('kiosk_enabled');
            $table->boolean('holiday_mode')->default(false)->after('kiosk_rates');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['gst_number', 'csc_id', 'logo_url', 'working_hours', 'kiosk_enabled', 'kiosk_rates', 'holiday_mode']);
        });
    }
};
