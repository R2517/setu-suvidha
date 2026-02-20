<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docslip_service_document', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docslip_service_id')->constrained()->onDelete('cascade');
            $table->foreignId('docslip_document_id')->constrained()->onDelete('cascade');
            $table->unique(['docslip_service_id', 'docslip_document_id'], 'ds_service_document_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docslip_service_document');
    }
};
