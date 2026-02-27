<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->index('type', 'wallet_transactions_type_index');
        });

        Schema::table('form_submissions', function (Blueprint $table) {
            $table->index('form_type', 'form_submissions_form_type_index');
        });

        Schema::table('billing_sales', function (Blueprint $table) {
            $table->index('created_at', 'billing_sales_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropIndex('wallet_transactions_type_index');
        });

        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropIndex('form_submissions_form_type_index');
        });

        Schema::table('billing_sales', function (Blueprint $table) {
            $table->dropIndex('billing_sales_created_at_index');
        });
    }
};
