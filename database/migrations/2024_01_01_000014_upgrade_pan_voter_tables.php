<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PAN Card — add status, aadhar, cheque payment mode
        Schema::table('pan_card_applications', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending')->after('application_type');
            $table->string('aadhar_number', 12)->nullable()->after('applicant_name');
        });
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE pan_card_applications MODIFY COLUMN payment_mode ENUM('cash','online','upi','cheque') DEFAULT 'cash'");
        }

        // Voter ID — add status, aadhar, cheque payment mode
        Schema::table('voter_id_applications', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending')->after('application_type');
            $table->string('aadhar_number', 12)->nullable()->after('applicant_name');
        });
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE voter_id_applications MODIFY COLUMN payment_mode ENUM('cash','online','upi','cheque') DEFAULT 'cash'");
        }
    }

    public function down(): void
    {
        Schema::table('pan_card_applications', function (Blueprint $table) {
            $table->dropColumn(['status', 'aadhar_number']);
        });
        Schema::table('voter_id_applications', function (Blueprint $table) {
            $table->dropColumn(['status', 'aadhar_number']);
        });
    }
};
