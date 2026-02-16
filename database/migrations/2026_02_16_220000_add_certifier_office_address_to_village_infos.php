<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('village_infos', function (Blueprint $table) {
            $table->string('certifier_office_address', 255)->nullable()->after('certifier_contact');
        });
    }

    public function down(): void
    {
        Schema::table('village_infos', function (Blueprint $table) {
            $table->dropColumn('certifier_office_address');
        });
    }
};
