<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('bond_formats')->insert([
            'slug'           => 'marriage-affidavit',
            'title_en'       => 'Marriage Affidavit',
            'title_mr'       => 'प्रथम विवाह नोंद प्रतिज्ञापत्र',
            'description_mr' => 'विवाह नोंद करण्यासाठी पती–पत्नींचे सत्यप्रतिज्ञेवर प्रतिज्ञापत्र (First Marriage Registration Affidavit)',
            'fee'            => 5.00,
            'icon'           => 'heart',
            'icon_bg_color'  => 'bg-pink-500',
            'is_active'      => true,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('bond_formats')->where('slug', 'marriage-affidavit')->delete();
    }
};
