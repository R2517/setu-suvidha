<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('mobile', 15)->nullable();
            $table->string('aadhaar_last_four', 4)->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('total_visits')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0);
            $table->decimal('total_due', 12, 2)->default(0);
            $table->timestamps();

            $table->index(['user_id', 'mobile']);
            $table->index(['user_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_customers');
    }
};
