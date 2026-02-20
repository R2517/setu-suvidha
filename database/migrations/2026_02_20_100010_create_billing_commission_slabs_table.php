<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_commission_slabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_from', 12, 2)->default(0);
            $table->decimal('amount_to', 12, 2)->default(0);
            $table->decimal('commission_percent', 5, 2)->default(0);
            $table->decimal('balance_enquiry_rate', 10, 2)->default(0);
            $table->decimal('mini_statement_rate', 10, 2)->default(0);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_commission_slabs');
    }
};
