<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bandkam_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('applicant_name');
            $table->string('mobile_number', 15)->nullable();
            $table->string('aadhar_number', 12)->nullable();
            $table->date('dob')->nullable();
            $table->string('district', 100)->nullable();
            $table->string('taluka', 100)->nullable();
            $table->string('village', 100)->nullable();
            $table->enum('registration_type', ['new', 'renewal'])->default('new');
            $table->string('application_number', 50)->nullable();
            $table->enum('status', ['pending', 'activated', 'expired'])->default('pending');
            $table->date('form_date')->nullable();
            $table->date('appointment_date')->nullable();
            $table->date('activation_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('online_date')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('received_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->enum('payment_mode', ['cash', 'online', 'upi'])->default('cash');
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bandkam_registrations');
    }
};
