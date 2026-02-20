<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── 1A. profiles — add only columns that don't exist yet ───
        // Already exist: full_name_mr, shop_pic, profile_pic, business_categories, csc_id
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'setu_id')) {
                $table->string('setu_id', 50)->nullable()->after('csc_id');
            }
            if (!Schema::hasColumn('profiles', 'whatsapp_same')) {
                $table->boolean('whatsapp_same')->default(true)->after('mobile');
            }
            if (!Schema::hasColumn('profiles', 'whatsapp_number')) {
                $table->string('whatsapp_number', 15)->nullable()->after('mobile');
            }
            if (!Schema::hasColumn('profiles', 'bank_name')) {
                $table->string('bank_name', 100)->nullable()->after('address');
            }
            if (!Schema::hasColumn('profiles', 'account_number')) {
                $table->string('account_number', 30)->nullable()->after('address');
            }
            if (!Schema::hasColumn('profiles', 'ifsc_code')) {
                $table->string('ifsc_code', 20)->nullable()->after('address');
            }
            if (!Schema::hasColumn('profiles', 'upi_id')) {
                $table->string('upi_id', 100)->nullable()->after('address');
            }
            if (!Schema::hasColumn('profiles', 'qr_code_pic')) {
                $table->string('qr_code_pic', 255)->nullable()->after('address');
            }
            if (!Schema::hasColumn('profiles', 'about_center')) {
                $table->text('about_center')->nullable()->after('address');
            }
            if (!Schema::hasColumn('profiles', 'google_map_link')) {
                $table->string('google_map_link', 500)->nullable()->after('address');
            }
            if (!Schema::hasColumn('profiles', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('address');
            }
            if (!Schema::hasColumn('profiles', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('address');
            }
            if (!Schema::hasColumn('profiles', 'is_public_approved')) {
                $table->boolean('is_public_approved')->default(false)->after('is_active');
            }
        });

        // ─── 1B. form_pricing — audience column ───
        if (!Schema::hasColumn('form_pricing', 'audience')) {
            Schema::table('form_pricing', function (Blueprint $table) {
                $table->enum('audience', ['vle', 'public'])->default('vle')->after('is_active');
            });
        }

        // ─── 1C. subscription_plans — new columns ───
        Schema::table('subscription_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_plans', 'plan_type')) {
                $table->enum('plan_type', ['monthly', 'quarterly', 'half_yearly', 'yearly'])->default('monthly')->after('name');
            }
            if (!Schema::hasColumn('subscription_plans', 'discount_percent')) {
                $table->decimal('discount_percent', 5, 2)->default(0)->after('features');
            }
            if (!Schema::hasColumn('subscription_plans', 'trial_days')) {
                $table->integer('trial_days')->default(15)->after('features');
            }
            if (!Schema::hasColumn('subscription_plans', 'razorpay_plan_id')) {
                $table->string('razorpay_plan_id', 100)->nullable()->after('features');
            }
        });

        // ─── 1D. vle_subscriptions — new columns ───
        Schema::table('vle_subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('vle_subscriptions', 'razorpay_subscription_id')) {
                $table->string('razorpay_subscription_id', 100)->nullable()->after('razorpay_payment_id');
            }
            if (!Schema::hasColumn('vle_subscriptions', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('razorpay_payment_id');
            }
            if (!Schema::hasColumn('vle_subscriptions', 'auto_renew')) {
                $table->boolean('auto_renew')->default(true)->after('razorpay_payment_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $cols = ['setu_id', 'whatsapp_same', 'whatsapp_number', 'bank_name', 'account_number',
                     'ifsc_code', 'upi_id', 'qr_code_pic', 'about_center', 'google_map_link',
                     'latitude', 'longitude', 'is_public_approved'];
            $drop = array_filter($cols, fn($c) => Schema::hasColumn('profiles', $c));
            if ($drop) $table->dropColumn($drop);
        });

        if (Schema::hasColumn('form_pricing', 'audience')) {
            Schema::table('form_pricing', function (Blueprint $table) {
                $table->dropColumn('audience');
            });
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            $cols = ['plan_type', 'discount_percent', 'trial_days', 'razorpay_plan_id'];
            $drop = array_filter($cols, fn($c) => Schema::hasColumn('subscription_plans', $c));
            if ($drop) $table->dropColumn($drop);
        });

        Schema::table('vle_subscriptions', function (Blueprint $table) {
            $cols = ['razorpay_subscription_id', 'trial_ends_at', 'auto_renew'];
            $drop = array_filter($cols, fn($c) => Schema::hasColumn('vle_subscriptions', $c));
            if ($drop) $table->dropColumn($drop);
        });
    }
};
