<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add maintenance_amount to subscription_plans
        Schema::table('subscription_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_plans', 'maintenance_amount')) {
                $table->decimal('maintenance_amount', 8, 2)->default(0)->after('price');
            }
        });

        // Add subscription tracking columns to vle_subscriptions
        Schema::table('vle_subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('vle_subscriptions', 'is_trial')) {
                $table->boolean('is_trial')->default(false)->after('status');
            }
            if (!Schema::hasColumn('vle_subscriptions', 'maintenance_paid')) {
                $table->boolean('maintenance_paid')->default(false)->after('is_trial');
            }
            if (!Schema::hasColumn('vle_subscriptions', 'plan_amount_paid')) {
                $table->boolean('plan_amount_paid')->default(false)->after('maintenance_paid');
            }
        });

        // Add signup_bonus_given to profiles to prevent double bonus
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'signup_bonus_given')) {
                $table->boolean('signup_bonus_given')->default(false)->after('wallet_balance');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_plans', 'maintenance_amount')) {
                $table->dropColumn('maintenance_amount');
            }
        });

        Schema::table('vle_subscriptions', function (Blueprint $table) {
            $cols = ['is_trial', 'maintenance_paid', 'plan_amount_paid'];
            $drop = array_filter($cols, fn($c) => Schema::hasColumn('vle_subscriptions', $c));
            if ($drop) $table->dropColumn($drop);
        });

        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'signup_bonus_given')) {
                $table->dropColumn('signup_bonus_given');
            }
        });
    }
};
