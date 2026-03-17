<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name_en' => 'Government Schemes', 'name_mr' => 'सरकारी योजना', 'slug' => 'government-schemes', 'icon' => 'landmark', 'sort_order' => 1],
            ['name_en' => 'Certificates & Documents', 'name_mr' => 'प्रमाणपत्रे व कागदपत्रे', 'slug' => 'certificates-documents', 'icon' => 'file-text', 'sort_order' => 2],
            ['name_en' => 'How-To Guides', 'name_mr' => 'मार्गदर्शक', 'slug' => 'how-to-guides', 'icon' => 'book-open', 'sort_order' => 3],
            ['name_en' => 'ID Cards', 'name_mr' => 'ओळखपत्रे', 'slug' => 'id-cards', 'icon' => 'credit-card', 'sort_order' => 4],
            ['name_en' => 'VLE Business Tips', 'name_mr' => 'VLE व्यवसाय टिप्स', 'slug' => 'vle-business-tips', 'icon' => 'trending-up', 'sort_order' => 5],
            ['name_en' => 'News & Updates', 'name_mr' => 'बातम्या व अपडेट्स', 'slug' => 'news-updates', 'icon' => 'newspaper', 'sort_order' => 6],
            ['name_en' => 'Registration', 'name_mr' => 'नोंदणी', 'slug' => 'registration', 'icon' => 'user-plus', 'sort_order' => 7],
            ['name_en' => 'Financial Services', 'name_mr' => 'आर्थिक सेवा', 'slug' => 'financial-services', 'icon' => 'wallet', 'sort_order' => 8],
        ];

        foreach ($categories as $category) {
            BlogCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
