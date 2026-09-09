<?php

namespace App\Console\Commands\Tenant;

use App\Models\Tenant;
use App\Models\Tenant\Contracting\ChartOfAccount;
use Database\Seeders\Tenant\ChartOfAccountsSeeder;
use Illuminate\Console\Command;

class SeedChartOfAccountsCommand extends Command
{
    protected $signature = 'tenant:seed-chart-of-accounts {--tenant=} {--dry-run=1 : Report what would happen without writing anything (default on; pass --dry-run=0 to actually seed)}';

    protected $description = 'Seed chart_of_accounts for tenants that were created before ChartOfAccountsSeeder ran automatically';

    public function handle(): int
    {
        $tenantId = $this->option('tenant');
        $dryRun = (bool) $this->option('dry-run');

        $tenants = $tenantId
            ? Tenant::where('id', $tenantId)->get()
            : Tenant::all();

        if ($dryRun) {
            $this->warn('DRY RUN — nothing will be written. Pass --dry-run=0 to seed for real.');
        }

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            $dryRun ? $this->previewTenant($tenant) : $this->seedTenant($tenant);
            tenancy()->end();
        }

        return self::SUCCESS;
    }

    protected function previewTenant(Tenant $tenant): void
    {
        $current = ChartOfAccount::query()->count();
        $wouldCreate = $current === 0 ? 42 : 0;

        $this->info("[{$tenant->id}] chart_of_accounts rows: {$current}. Would create: {$wouldCreate}.");

        $unresolved = $this->unresolvedMapCodes();
        if ($unresolved->isEmpty()) {
            $this->line('  All account_coa_map codes would resolve to an active chart_of_accounts row.');
        } else {
            $this->line('  Unresolved account_coa_map codes: ' . $unresolved->implode(', '));
        }
    }

    protected function seedTenant(Tenant $tenant): void
    {
        $before = ChartOfAccount::query()->count();

        if ($before > 0) {
            $this->info("[{$tenant->id}] already has {$before} chart_of_accounts rows — skipping.");
            return;
        }

        (new ChartOfAccountsSeeder())->run();

        $after = ChartOfAccount::query()->count();
        $this->info("[{$tenant->id}] seeded {$after} chart_of_accounts rows.");

        $unresolved = $this->unresolvedMapCodes();
        if ($unresolved->isNotEmpty()) {
            $this->warn("[{$tenant->id}] account_coa_map codes still unresolved: " . $unresolved->implode(', '));
        }
    }

    /**
     * @return \Illuminate\Support\Collection<int, string>
     */
    protected function unresolvedMapCodes()
    {
        $codes = collect(config('tenant.account_coa_map', []))->unique()->values();
        $activeCodes = ChartOfAccount::where('is_active', 1)->pluck('code');

        return $codes->diff($activeCodes)->values();
    }
}
