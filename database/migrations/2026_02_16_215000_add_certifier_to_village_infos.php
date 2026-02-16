<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('village_infos', function (Blueprint $table) {
            $table->string('certifier_name')->nullable()->after('verifier_name');
            $table->string('certifier_designation')->nullable()->after('certifier_name');
            $table->string('certifier_contact', 15)->nullable()->after('certifier_designation');
        });
    }

    public function down(): void
    {
        Schema::table('village_infos', function (Blueprint $table) {
            $table->dropColumn(['certifier_name', 'certifier_designation', 'certifier_contact']);
        });
    }
};
