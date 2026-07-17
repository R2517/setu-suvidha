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
        Schema::create('ad_settings', function (Blueprint $table) {
            $table->id();
            $table->string('slot_name')->unique(); // e.g., 'left_sidebar', 'right_sidebar', 'top_banner', 'bottom_banner'
            $table->boolean('is_active')->default(true);
            $table->enum('type', ['image', 'script'])->default('image');
            $table->text('content')->nullable(); // Image path or AdSense script
            $table->string('target_url')->nullable(); // URL to redirect if image clicked
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_settings');
    }
};
