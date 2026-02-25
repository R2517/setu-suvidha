<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'state')) {
                $table->string('state', 100)->nullable()->after('address');
            }

            if (!Schema::hasColumn('profiles', 'village')) {
                $table->string('village', 150)->nullable()->after('taluka');
            }

            if (!Schema::hasColumn('profiles', 'promo_code')) {
                $table->string('promo_code', 50)->nullable()->after('village');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $drop = [];

            if (Schema::hasColumn('profiles', 'promo_code')) {
                $drop[] = 'promo_code';
            }

            if (Schema::hasColumn('profiles', 'village')) {
                $drop[] = 'village';
            }

            if (Schema::hasColumn('profiles', 'state')) {
                $drop[] = 'state';
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
