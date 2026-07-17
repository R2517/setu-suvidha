<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $formats = [
            [
                'slug'           => 'rajpatra-marathi',
                'title_en'       => 'Rajpatra Marathi',
                'title_mr'       => 'राजपत्र मराठी',
                'description_mr' => 'महाराष्ट्र शासन राजपत्र — मराठी नाव बदल नोटीस (Gazette Name Change Notice).',
                'fee'            => 1.00,
                'icon'           => 'scroll-text',
                'icon_bg_color'  => 'bg-amber-500',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'slug'           => 'rajpatra-english',
                'title_en'       => 'Rajpatra English',
                'title_mr'       => 'राजपत्र इंग्रजी',
                'description_mr' => 'Maharashtra Government Gazette — English Name Change Notice.',
                'fee'            => 1.00,
                'icon'           => 'file-text',
                'icon_bg_color'  => 'bg-blue-500',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];

        foreach ($formats as $f) {
            $exists = DB::table('bond_formats')->where('slug', $f['slug'])->exists();
            if (!$exists) {
                DB::table('bond_formats')->insert($f);
            }
        }
    }

    public function down(): void
    {
        DB::table('bond_formats')->whereIn('slug', [
            'rajpatra-marathi', 'rajpatra-english',
        ])->delete();
    }
};
