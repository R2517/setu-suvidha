<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_payment_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->nullable()->constrained('subscription_plans')->nullOnDelete();
            $table->foreignId('current_subscription_id')->nullable()->constrained('vle_subscriptions')->nullOnDelete();
            $table->enum('action', ['activate', 'change', 'activate_now']);
            $table->unsignedInteger('amount_paise');
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('razorpay_order_id', 100)->unique();
            $table->string('razorpay_payment_id', 100)->nullable()->unique();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['action', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payment_orders');
    }
};
