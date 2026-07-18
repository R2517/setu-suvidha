<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('bond_formats')->updateOrInsert(
            ['slug' => 'mahavitaran-affidavit'],
            [
                'title_en' => 'Mahavitaran Affidavit',
                'title_mr' => 'महावितरण प्रतिज्ञापत्र',
                'description_mr' => 'महावितरण वीज जोडणी प्रतिज्ञापत्र',
                'fee' => 10.00,
                'icon' => 'zap',
                'icon_bg_color' => 'bg-yellow-100',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('bond_formats')->where('slug', 'mahavitaran-affidavit')->delete();
    }
};
