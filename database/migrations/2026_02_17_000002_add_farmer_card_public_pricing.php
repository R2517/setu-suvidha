<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\FormPricing;

return new class extends Migration
{
    public function up(): void
    {
        FormPricing::firstOrCreate(
            ['form_type' => 'farmer_id_card_public'],
            [
                'form_name' => 'शेतकरी ओळखपत्र — Public (Farmer ID Online)',
                'price' => 0.00, // Free for now
                'is_active' => true,
            ]
        );
    }

    public function down(): void
    {
        FormPricing::where('form_type', 'farmer_id_card_public')->delete();
    }
};
