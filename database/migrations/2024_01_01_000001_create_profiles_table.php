<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('email');
            $table->string('shop_name')->nullable();
            $table->enum('shop_type', ['setu', 'csc', 'other'])->nullable();
            $table->string('mobile', 15)->nullable();
            $table->text('address')->nullable();
            $table->string('district', 100)->nullable();
            $table->string('taluka', 100)->nullable();
            $table->decimal('wallet_balance', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
