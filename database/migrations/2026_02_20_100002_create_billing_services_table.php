<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('category')->default('General');
            $table->decimal('default_price', 10, 2)->default(0);
            $table->decimal('cost_price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system_default')->default(false);
            $table->unsignedInteger('display_order')->default(0);
            $table->unsignedBigInteger('override_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_services');
    }
};
