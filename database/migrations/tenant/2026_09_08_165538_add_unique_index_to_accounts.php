<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $duplicates = DB::table('accounts')
            ->select('code', 'branch_id', DB::raw('COUNT(*) as duplicate_count'))
            ->groupBy('code', 'branch_id')
            ->having('duplicate_count', '>', 1)
            ->get();

        if ($duplicates->isNotEmpty()) {
            throw new \RuntimeException(
                'Refusing to add unique index on accounts(code, branch_id): '
                .$duplicates->count().' duplicate (code, branch_id) group(s) exist. '
                .'Run `tenant:merge-duplicate-accounts` (dry-run first) to resolve them before re-running this migration.'
            );
        }

        Schema::table('accounts', function (Blueprint $table) {
            $table->unique(['code', 'branch_id'], 'accounts_code_branch_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropUnique('accounts_code_branch_id_unique');
        });
    }
};
