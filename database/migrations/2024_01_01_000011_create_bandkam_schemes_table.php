<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bandkam_schemes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->nullable()->constrained('bandkam_registrations')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('scheme_type', 50);
            $table->string('applicant_name');
            $table->string('beneficiary_name')->nullable();
            $table->string('student_name')->nullable();
            $table->string('scholarship_category', 100)->nullable();
            $table->string('year', 20)->nullable();
            $table->enum('status', ['pending', 'applied', 'approved', 'delivered'])->default('applied');
            $table->date('apply_date')->nullable();
            $table->date('appointment_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('received_amount', 10, 2)->default(0);
            $table->decimal('commission_percent', 5, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->enum('payment_mode', ['cash', 'online', 'upi'])->default('cash');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bandkam_schemes');
    }
};
