<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('category');
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('payment_mode', ['cash', 'upi', 'online'])->default('cash');
            $table->date('expense_date');
            $table->string('receipt_url')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'expense_date']);
            $table->index(['user_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_expenses');
    }
};
