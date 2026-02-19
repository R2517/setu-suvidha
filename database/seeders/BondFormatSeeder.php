<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BondFormat;

class BondFormatSeeder extends Seeder
{
    public function run(): void
    {
        $formats = [
            [
                'slug'           => 'rent-agreement',
                'title_en'       => 'Rent Agreement',
                'title_mr'       => 'भाडे करारनामा',
                'description_mr' => 'घर किंवा दुकानाचा 11 महिन्यांचा अधिकृत भाडे करारनामा तयार करा.',
                'fee'            => 5.00,
                'icon'           => 'home',
                'icon_bg_color'  => 'bg-green-500',
            ],
            [
                'slug'           => 'partition-deed',
                'title_en'       => 'Partition Deed',
                'title_mr'       => 'कौटुंबिक वाटणीपत्र',
                'description_mr' => 'वडिलोपार्जित मिळकतीची आपापसात वाटणी करण्यासाठी कायदेशीर वाटणीपत्र.',
                'fee'            => 5.00,
                'icon'           => 'users',
                'icon_bg_color'  => 'bg-orange-400',
            ],
            [
                'slug'           => 'gift-deed',
                'title_en'       => 'Gift Deed',
                'title_mr'       => 'बक्षीस पत्र',
                'description_mr' => 'रक्ताच्या नात्यात जमीन किंवा घर बक्षीस देण्यासाठी लागणारे बक्षीस पत्र.',
                'fee'            => 5.00,
                'icon'           => 'gift',
                'icon_bg_color'  => 'bg-purple-600',
            ],
            [
                'slug'           => 'release-deed',
                'title_en'       => 'Release Deed',
                'title_mr'       => 'हक्कसोड पत्र',
                'description_mr' => 'एखाद्या मिळकतीवरील आपला कायदेशीर हक्क सोडून देण्यासाठीचे पत्र.',
                'fee'            => 5.00,
                'icon'           => 'heart-handshake',
                'icon_bg_color'  => 'bg-orange-500',
            ],
            [
                'slug'           => 'cast-validity-education',
                'title_en'       => 'Cast Validity',
                'title_mr'       => 'जात वैधता',
                'description_mr' => 'जात प्रमाणपत्र पडताळणीसाठी शपथपत्र — शिक्षण, निवडणूक, सेवा व इतर (नमुना ३ + १७/२१/१९/२३).',
                'fee'            => 5.00,
                'icon'           => 'scroll-text',
                'icon_bg_color'  => 'bg-teal-500',
            ],
        ];

        foreach ($formats as $f) {
            BondFormat::updateOrCreate(['slug' => $f['slug']], $f);
        }
    }
}
