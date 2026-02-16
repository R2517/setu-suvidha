<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // D2: Add soft deletes to critical tables
        $tables = ['form_submissions', 'pan_card_applications', 'voter_id_applications', 'bandkam_registrations'];
        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->softDeletes();
                });
            }
        }

        // F5: Add database indexes on frequently queried columns
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->index(['user_id', 'form_type'], 'fs_user_formtype_idx');
        });

        Schema::table('pan_card_applications', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'pan_user_status_idx');
            $table->index(['user_id', 'created_at'], 'pan_user_created_idx');
        });

        Schema::table('voter_id_applications', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'voter_user_status_idx');
            $table->index(['user_id', 'created_at'], 'voter_user_created_idx');
        });

        Schema::table('bandkam_registrations', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'bandkam_user_status_idx');
            $table->index(['user_id', 'created_at'], 'bandkam_user_created_idx');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'wt_user_created_idx');
            $table->index('reference_id', 'wt_reference_idx');
        });
    }

    public function down(): void
    {
        $tables = ['form_submissions', 'pan_card_applications', 'voter_id_applications', 'bandkam_registrations'];
        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropSoftDeletes();
                });
            }
        }

        Schema::table('form_submissions', fn(Blueprint $t) => $t->dropIndex('fs_user_formtype_idx'));
        Schema::table('pan_card_applications', function (Blueprint $t) {
            $t->dropIndex('pan_user_status_idx');
            $t->dropIndex('pan_user_created_idx');
        });
        Schema::table('voter_id_applications', function (Blueprint $t) {
            $t->dropIndex('voter_user_status_idx');
            $t->dropIndex('voter_user_created_idx');
        });
        Schema::table('bandkam_registrations', function (Blueprint $t) {
            $t->dropIndex('bandkam_user_status_idx');
            $t->dropIndex('bandkam_user_created_idx');
        });
        Schema::table('wallet_transactions', function (Blueprint $t) {
            $t->dropIndex('wt_user_created_idx');
            $t->dropIndex('wt_reference_idx');
        });
    }
};
