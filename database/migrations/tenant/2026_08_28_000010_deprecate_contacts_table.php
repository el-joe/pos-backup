<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE contacts ADD COLUMN deprecated_at TIMESTAMP NULL DEFAULT NULL");
        DB::statement("UPDATE contacts SET deprecated_at = NOW()");
        DB::statement("ALTER TABLE contacts COMMENT 'DEPRECATED — use users table instead'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE contacts DROP COLUMN deprecated_at");
    }
};
