<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormPricing;

class FormPricingSeeder extends Seeder
{
    public function run(): void
    {
        $forms = [
            ['form_type' => 'hamipatra', 'form_name' => 'हमीपत्र (Disclaimer)', 'price' => 2.00],
            ['form_type' => 'self_declaration', 'form_name' => 'स्वयंघोषणापत्र', 'price' => 2.00],
            ['form_type' => 'grievance', 'form_name' => 'तक्रार नोंदणी (Grievance)', 'price' => 2.00],
            ['form_type' => 'new_application', 'form_name' => 'नवीन अर्ज (New Application)', 'price' => 2.00],
            ['form_type' => 'caste_validity', 'form_name' => 'जात पडताळणी', 'price' => 3.00],
            ['form_type' => 'income_cert', 'form_name' => 'उत्पन्नाचे स्वयंघोषणापत्र', 'price' => 5.00],
            ['form_type' => 'rajpatra_marathi', 'form_name' => 'राजपत्र मराठी (Gazette)', 'price' => 5.00],
            ['form_type' => 'rajpatra_english', 'form_name' => 'राजपत्र English (Gazette)', 'price' => 5.00],
            ['form_type' => 'rajpatra_affidavit_712', 'form_name' => 'राजपत्र ७/१२ शपथपत्र', 'price' => 5.00],
            ['form_type' => 'farmer_id_card', 'form_name' => 'शेतकरी ओळखपत्र (Farmer ID)', 'price' => 3.00],
        ];

        foreach ($forms as $form) {
            FormPricing::updateOrCreate(
                ['form_type' => $form['form_type']],
                $form
            );
        }
    }
}
