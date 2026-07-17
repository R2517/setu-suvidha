<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Form 2 - Caste Certificate Affidavit (Applicant)
        DB::table('bond_formats')->updateOrInsert(
            ['slug' => 'caste-form2'],
            [
                'title_en'       => 'Caste Certificate Affidavit (Form 2)',
                'title_mr'       => 'जातीचे प्रमाणपत्र प्रतिज्ञापत्र (फॉर्म २)',
                'description_mr' => 'अनुसूचित जाती / जमाती / ओबीसी / एसबीसी जात प्रमाणपत्रासाठी अर्जदाराचे प्रतिज्ञापत्र',
                'fee'            => 1.00,
                'icon'           => 'file-check',
                'icon_bg_color'  => 'bg-blue-600',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]
        );

        // Form 3 - Affidavit of Claimant/Parent(s)
        DB::table('bond_formats')->updateOrInsert(
            ['slug' => 'caste-form3'],
            [
                'title_en'       => 'Affidavit of Claimant/Parent (Form 3)',
                'title_mr'       => 'दावेदार / पालक प्रतिज्ञापत्र (फॉर्म ३)',
                'description_mr' => 'जाती / जमाती दाव्यासाठी दावेदार / पालकांचे प्रतिज्ञापत्र (CPC Order 18)',
                'fee'            => 1.00,
                'icon'           => 'users',
                'icon_bg_color'  => 'bg-purple-600',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('bond_formats')->whereIn('slug', ['caste-form2', 'caste-form3'])->delete();
    }
};
