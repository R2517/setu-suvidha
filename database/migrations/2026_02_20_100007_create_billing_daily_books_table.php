<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_daily_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('business_date');
            $table->decimal('opening_cash', 12, 2)->default(0);
            $table->decimal('closing_cash', 12, 2)->nullable();
            $table->decimal('expected_cash', 12, 2)->default(0);
            $table->decimal('difference', 12, 2)->default(0);
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->unsignedInteger('close_version')->default(0);
            $table->text('closing_notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'business_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_daily_books');
    }
};
