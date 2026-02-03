<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $uniqueIndexName = 'emp_leave_ent_emp_leave_year_uniq';

        if (! Schema::hasTable('employee_leave_entitlements')) {
            Schema::create('employee_leave_entitlements', function (Blueprint $table) use ($uniqueIndexName) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
                $table->foreignId('leave_type_id')->constrained()->cascadeOnDelete();
                $table->year('year');
                $table->decimal('total_days', 8, 2)->default(0);
                $table->decimal('used_days', 8, 2)->default(0);
                $table->decimal('pending_days', 8, 2)->default(0);
                $table->decimal('remaining_days', 8, 2)->default(0);
                $table->decimal('carried_forward_days', 8, 2)->default(0);
                $table->timestamps();

                $table->unique(['employee_id', 'leave_type_id', 'year'], $uniqueIndexName);
            });

            return;
        }

        $indexExists = collect(DB::select('SHOW INDEX FROM `employee_leave_entitlements`'))
            ->contains(fn ($row) => ($row->Key_name ?? null) === $uniqueIndexName);

        if (! $indexExists) {
            Schema::table('employee_leave_entitlements', function (Blueprint $table) use ($uniqueIndexName) {
                $table->unique(['employee_id', 'leave_type_id', 'year'], $uniqueIndexName);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_leave_entitlements');
    }
};
