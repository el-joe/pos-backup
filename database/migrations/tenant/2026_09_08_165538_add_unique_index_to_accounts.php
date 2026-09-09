<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const INDEX_NAME = 'accounts_code_branch_id_unique';

    /**
     * Run the migrations.
     *
     * 2026_09_08_165000_migrate_system_account_codes already creates this same index
     * (by the same name) at the end of its own up() — on a fresh install that migration
     * runs first (earlier timestamp) and leaves the index in place, so this one is a
     * no-op guarded by indexExists() rather than a hard failure.
     */
    public function up(): void
    {
        if ($this->indexExists('accounts', self::INDEX_NAME)) {
            return;
        }

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
            $table->unique(['code', 'branch_id'], self::INDEX_NAME);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->indexExists('accounts', self::INDEX_NAME)) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->dropUnique(self::INDEX_NAME);
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        return collect(Schema::getIndexes($table))->contains('name', $indexName);
    }
};
