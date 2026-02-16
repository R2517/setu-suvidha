<?php

namespace Database\Seeders;

use App\Models\FormSubmission;
use Illuminate\Database\Seeder;

class FarmerTestSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            ['mr' => 'राजेश पाटील', 'en' => 'Rajesh Patil'],
            ['mr' => 'सुनील जाधव', 'en' => 'Sunil Jadhav'],
            ['mr' => 'प्रकाश शिंदे', 'en' => 'Prakash Shinde'],
            ['mr' => 'अनिल मोरे', 'en' => 'Anil More'],
            ['mr' => 'विजय कदम', 'en' => 'Vijay Kadam'],
            ['mr' => 'संजय गायकवाड', 'en' => 'Sanjay Gaikwad'],
            ['mr' => 'महेश पवार', 'en' => 'Mahesh Pawar'],
            ['mr' => 'दिनेश भोसले', 'en' => 'Dinesh Bhosale'],
            ['mr' => 'रामदास चव्हाण', 'en' => 'Ramdas Chavhan'],
            ['mr' => 'गणेश वाघमारे', 'en' => 'Ganesh Waghmare'],
            ['mr' => 'बाळासाहेब देशमुख', 'en' => 'Balasaheb Deshmukh'],
            ['mr' => 'नारायण सोनवणे', 'en' => 'Narayan Sonawane'],
            ['mr' => 'किशोर निकम', 'en' => 'Kishor Nikam'],
            ['mr' => 'तुकाराम इंगळे', 'en' => 'Tukaram Ingale'],
            ['mr' => 'भरत साळुंखे', 'en' => 'Bharat Salunkhe'],
            ['mr' => 'ज्ञानेश्वर लोखंडे', 'en' => 'Dnyaneshwar Lokhande'],
            ['mr' => 'शंकर कांबळे', 'en' => 'Shankar Kamble'],
            ['mr' => 'पांडुरंग गोरे', 'en' => 'Pandurang Gore'],
            ['mr' => 'दत्तात्रय माने', 'en' => 'Dattatray Mane'],
            ['mr' => 'सुरेश बागल', 'en' => 'Suresh Bagal'],
            ['mr' => 'योगेश राउत', 'en' => 'Yogesh Raut'],
            ['mr' => 'अशोक ठाकरे', 'en' => 'Ashok Thakre'],
            ['mr' => 'मारुती गावडे', 'en' => 'Maruti Gawde'],
            ['mr' => 'विश्वनाथ कुलकर्णी', 'en' => 'Vishwanath Kulkarni'],
            ['mr' => 'हनुमंत जगताप', 'en' => 'Hanumant Jagtap'],
        ];

        $districts = ['Pune','Nashik','Amravati','Nagpur','Aurangabad','Kolhapur','Solapur','Satara','Sangli','Jalgaon','Ahmednagar','Latur','Nanded','Beed','Osmanabad','Wardha','Yavatmal','Akola','Buldhana','Washim','Hingoli','Parbhani','Raigad','Ratnagiri','Sindhudurg'];
        $talukas = ['Haveli','Maval','Junnar','Igatpuri','Dindori','Achalpur','Kamthi','Paithan','Karvir','Pandharpur','Karad','Miraj','Bhusawal','Shrigonda','Ausa','Deoli','Pusad','Murtizapur','Shegaon','Chikhli','Mangrulpir','Sengaon','Mahad','Rajapur','Sawantwadi'];
        $villages = ['वडगाव','चिंचवड','कोथरूड','बावधन','आंबेगाव','शिरूर','राजगुरुनगर','मंचर','ओतूर','जुन्नर','आळेफाटा','नारायणगाव','खेड','भोर','वेल्हे','पुरंदर','सासवड','बारामती','इंदापूर','दौंड','लोणावळा','मावळ','मुळशी','चिपळूण','कुडाळ'];
        $genders = ['Male', 'Female'];

        for ($i = 0; $i < 25; $i++) {
            $n = $names[$i];
            $mob = '9' . rand(100000000, 999999999);
            $aadh = rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999);
            $dob = (1960 + rand(0, 35)) . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
            $fid = '27_' . rand(100, 999) . '_' . rand(1000, 9999) . '_' . rand(100000, 999999) . '_' . str_pad(rand(1, 9999), 6, '0', STR_PAD_LEFT);
            $area = round(rand(50, 500) / 100, 2);

            FormSubmission::create([
                'user_id' => 1,
                'form_type' => 'farmer_id_card',
                'applicant_name' => $n['mr'],
                'form_data' => [
                    'name_english' => $n['en'],
                    'dob' => $dob,
                    'gender' => $genders[rand(0, 1)],
                    'mobile' => $mob,
                    'aadhaar' => $aadh,
                    'farmer_id' => $fid,
                    'lives_at_farm' => true,
                    'land' => [
                        [
                            'district' => $districts[$i],
                            'taluka' => $talukas[$i],
                            'village' => $villages[$i],
                            'gat_no' => rand(10, 999) . '/' . rand(1, 20),
                            'khate_no' => (string) rand(100, 9999),
                            'area' => (string) $area,
                        ],
                    ],
                ],
            ]);
        }

        $this->command->info('25 farmer ID cards created successfully!');
    }
}
