<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pan_card_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('application_type', ['new', 'correction', 'reprint'])->default('new');
            $table->string('application_number', 100);
            $table->string('applicant_name');
            $table->date('dob')->nullable();
            $table->string('mobile_number', 15)->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('received_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->enum('payment_mode', ['cash', 'online', 'upi'])->default('cash');
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pan_card_applications');
    }
};
