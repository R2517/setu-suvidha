<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmer_card_orders', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no', 20)->unique();
            $table->string('applicant_name');
            $table->string('name_english');
            $table->date('dob')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('mobile', 15);
            $table->string('aadhaar', 14)->nullable();
            $table->string('farmer_id', 20)->nullable();
            $table->string('address_village')->nullable();
            $table->string('address_taluka')->nullable();
            $table->string('address_district')->nullable();
            $table->string('address_state')->default('महाराष्ट्र (Maharashtra)');
            $table->string('address_pincode', 10)->nullable();
            $table->string('photo')->nullable();
            $table->json('land_details')->nullable();
            $table->integer('amount')->default(5000); // paise (₹50)
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->integer('download_count')->default(0);
            $table->timestamp('last_downloaded_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->boolean('data_purged')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmer_card_orders');
    }
};
