<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level', 20)->default('error')->index();
            $table->string('message', 1000);
            $table->string('file', 500)->nullable();
            $table->unsignedInteger('line')->nullable();
            $table->string('url', 500)->nullable();
            $table->string('method', 10)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->text('trace')->nullable();
            $table->json('context')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->index('created_at');
            $table->index('is_resolved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
