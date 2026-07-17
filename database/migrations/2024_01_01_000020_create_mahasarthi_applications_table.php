<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasarthi_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('applicant_name');
            $table->text('aadhar_number')->nullable();
            $table->string('mobile_number', 15)->nullable();
            $table->string('application_number', 100)->nullable();

            $table->enum('status', ['applied', 'card_otp', 'ready_card', 'delivered'])->default('applied');
            $table->date('applied_date')->nullable();
            $table->date('card_otp_date')->nullable();
            $table->date('ready_card_date')->nullable();
            $table->date('delivered_date')->nullable();

            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('received_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->enum('payment_mode', ['cash', 'online', 'upi', 'cheque'])->default('cash');

            $table->unsignedInteger('print_count')->default(0);
            $table->timestamp('last_printed_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->softDeletes();

            $table->index('user_id');
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasarthi_applications');
    }
};
