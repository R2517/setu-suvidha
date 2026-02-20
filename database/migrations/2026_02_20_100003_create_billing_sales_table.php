<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('billing_customers')->nullOnDelete();
            $table->string('invoice_number', 30)->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone', 15)->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('received_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);
            $table->enum('payment_mode', ['cash', 'upi', 'online', 'split'])->default('cash');
            $table->decimal('cash_amount', 12, 2)->default(0);
            $table->decimal('online_amount', 12, 2)->default(0);
            $table->enum('payment_status', ['paid', 'unpaid', 'partial'])->default('unpaid');
            $table->text('remarks')->nullable();
            $table->date('sale_date');
            $table->boolean('is_deleted')->default(false);
            $table->string('delete_reason')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'sale_date']);
            $table->index(['user_id', 'payment_status']);
            $table->index(['user_id', 'invoice_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_sales');
    }
};
