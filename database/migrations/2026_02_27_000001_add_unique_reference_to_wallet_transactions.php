<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove any pre-existing duplicate (reference_id, type) rows before adding unique constraint.
        // Keep only the earliest record for each duplicate pair.
        DB::statement('
            DELETE t1 FROM wallet_transactions t1
            INNER JOIN wallet_transactions t2
            ON t1.reference_id = t2.reference_id
               AND t1.type = t2.type
               AND t1.reference_id IS NOT NULL
               AND t1.id > t2.id
        ');

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->unique(['type', 'reference_id'], 'wallet_tx_type_reference_unique');
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropUnique('wallet_tx_type_reference_unique');
        });
    }
};
