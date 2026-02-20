<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_kiosk_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('transaction_type', ['withdraw', 'deposit', 'balance', 'mini_statement']);
            $table->string('customer_name')->nullable();
            $table->string('customer_mobile', 15)->nullable();
            $table->string('aadhaar_last_four', 4)->nullable();
            $table->string('bank_name')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('manual_commission', 10, 2)->default(0);
            $table->decimal('portal_commission', 10, 2)->default(0);
            $table->date('transaction_date');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'transaction_date']);
            $table->index(['user_id', 'transaction_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_kiosk_transactions');
    }
};
