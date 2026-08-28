<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Central admins previously had no way to distinguish a super admin from a
     * regular admin (unlike tenant admins, which already have a `type` column).
     * Existing rows default to 'super_admin' so nobody currently able to use
     * the CPanel loses access after this migration runs.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'type')) {
                $table->enum('type', ['super_admin', 'admin'])->default('super_admin')->after('active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
