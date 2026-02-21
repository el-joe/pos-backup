<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attendance_logs')) {
            return;
        }

        Schema::table('attendance_logs', function (Blueprint $table) {
            // Previously unique(attendance_sheet_id, employee_id) prevented multiple check-in/out sessions per day.
            $table->dropUnique(['attendance_sheet_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('attendance_logs')) {
            return;
        }

        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->unique(['attendance_sheet_id', 'employee_id']);
        });
    }
};
