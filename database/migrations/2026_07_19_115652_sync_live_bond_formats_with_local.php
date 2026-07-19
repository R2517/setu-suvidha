<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('bond_formats')->truncate();
        $jsonPath = database_path('data/bond_formats_local.json');
        if (file_exists($jsonPath)) {
            $json = file_get_contents($jsonPath);
            $data = json_decode($json, true);
            foreach ($data as &$row) {
                if (isset($row['created_at'])) $row['created_at'] = substr(str_replace('T', ' ', $row['created_at']), 0, 19);
                if (isset($row['updated_at'])) $row['updated_at'] = substr(str_replace('T', ' ', $row['updated_at']), 0, 19);
            }
            DB::table('bond_formats')->insert($data);
        }
    }

    public function down(): void
    {
        //
    }
};
