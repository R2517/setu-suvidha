<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('card_crop_settings', function (Blueprint $table) {
            $table->id();
            $table->string('card_type')->unique(); // e.g., 'aadhaar_front', 'aadhaar_back', 'pan_card'
            $table->decimal('x_percent', 8, 4);
            $table->decimal('y_percent', 8, 4);
            $table->decimal('width_percent', 8, 4);
            $table->decimal('height_percent', 8, 4);
            $table->timestamps();
        });
        
        // Insert default values immediately
        DB::table('card_crop_settings')->insert([
            [
                'card_type' => 'aadhaar_front',
                'x_percent' => 5.7000,
                'y_percent' => 65.6000,
                'width_percent' => 40.7600,
                'height_percent' => 18.1800, // 40.76 / (85.6/54) = ~25.7 but let's just store width and calculate height dynamically, or store it. Let's store both.
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'card_type' => 'aadhaar_back',
                'x_percent' => 53.6000,
                'y_percent' => 65.6000,
                'width_percent' => 40.7600,
                'height_percent' => 18.1800,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'card_type' => 'pan_card',
                'x_percent' => 7.5000, // Approximate defaults
                'y_percent' => 15.0000,
                'width_percent' => 85.0000,
                'height_percent' => 53.6000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_crop_settings');
    }
};
