<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'बेसिक',
                'price' => 0,
                'duration_days' => 0,
                'features' => json_encode(['खाते तयार करा', 'सर्व फॉर्म्स वापरा', 'प्रति फॉर्म शुल्क', 'व्यवहार इतिहास']),
            ],
            [
                'name' => 'प्रो',
                'price' => 49,
                'duration_days' => 30,
                'features' => json_encode(['सर्व बेसिक फीचर्स', 'कमी शुल्क दर', 'प्राधान्य सपोर्ट', 'बल्क प्रिंट', 'अॅडव्हान्स रिपोर्ट्स']),
            ],
            [
                'name' => 'एंटरप्राइज',
                'price' => 0,
                'duration_days' => 0,
                'features' => json_encode(['सर्व प्रो फीचर्स', 'कस्टम ब्रँडिंग', 'API ऍक्सेस', 'डेडिकेटेड सपोर्ट', 'मल्टी-लोकेशन']),
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
