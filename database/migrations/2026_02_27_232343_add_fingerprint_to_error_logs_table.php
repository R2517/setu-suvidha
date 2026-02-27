<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('error_logs', function (Blueprint $table) {
            $table->string('fingerprint', 64)->nullable()->index()->after('level');
            $table->unsignedInteger('occurrence_count')->default(1)->after('fingerprint');
            $table->timestamp('last_seen_at')->nullable()->after('occurrence_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('error_logs', function (Blueprint $table) {
            $table->dropColumn(['fingerprint', 'occurrence_count', 'last_seen_at']);
        });
    }
};
