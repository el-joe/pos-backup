<?php

use App\Models\Tenant\Contracting\ChartOfAccount;
use Database\Seeders\Tenant\ChartOfAccountsSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ships alongside the LedgerBridgeService fix that stops an empty chart_of_accounts
     * from blocking postings — this migration is the actual fix for existing tenants.
     * Jobs\SeedDatabase (TenancyServiceProvider) only seeds new tenants; any tenant
     * created before that was wired up has zero chart_of_accounts rows.
     *
     * Idempotent: the seeder itself is (firstOrCreate/updateOrCreate on code), and this
     * migration also short-circuits when rows already exist, so re-running it is a no-op.
     */
    public function up(): void
    {
        if (!Schema::hasTable('chart_of_accounts')) {
            return;
        }

        if (ChartOfAccount::query()->exists()) {
            return;
        }

        (new ChartOfAccountsSeeder())->run();
    }

    /**
     * Never delete a chart of accounts — journal_entry_lines may already reference it.
     */
    public function down(): void
    {
        //
    }
};
