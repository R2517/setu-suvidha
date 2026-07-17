<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('bond_formats')->updateOrInsert(
            ['slug' => 'self-declaration'],
            [
                'title_en'       => 'Self Declaration (Prapatra-A)',
                'title_mr'       => 'स्वयंघोषणापत्र (प्रपत्र–अ)',
                'description_mr' => 'शासन निर्णय क्रमांक प्रसुधा १६.१४/३४५ अंतर्गत स्वयंघोषणापत्र प्रपत्र–अ. फोटो व सही अपलोड सह.',
                'fee'            => 5.00,
                'icon'           => 'file-check',
                'icon_bg_color'  => 'bg-emerald-500',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('bond_formats')->where('slug', 'self-declaration')->delete();
    }
};
