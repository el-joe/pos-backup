<?php

namespace App\Console\Commands\Tenant;

use App\Models\Tenant;
use App\Models\Tenant\Expense;
use App\Models\Tenant\FixedAsset;
use App\Services\DepreciationService;
use Illuminate\Console\Command;

class RunDepreciationCommand extends Command
{
    protected $signature = 'tenant:run-depreciation {--tenant=} {--year=} {--month=} {--dry-run=1}';
    protected $description = 'Post monthly depreciation for all fixed assets. Idempotent per asset per period. Dry-run by default.';

    public function handle(DepreciationService $depreciationService): int
    {
        $tenantId = $this->option('tenant');
        $year = (int) ($this->option('year') ?: now()->year);
        $month = (int) ($this->option('month') ?: now()->month);
        $dryRun = (bool) ((int) $this->option('dry-run'));

        $tenants = $tenantId
            ? Tenant::where('id', $tenantId)->get()
            : Tenant::all();

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            $this->info("Tenant: {$tenant->id} — period {$year}-".str_pad((string) $month, 2, '0', STR_PAD_LEFT).($dryRun ? ' (dry-run)' : ' (LIVE)'));
            $this->runForTenant($depreciationService, $year, $month, $dryRun);
            tenancy()->end();
        }

        if ($dryRun) {
            $this->newLine();
            $this->comment('Dry-run only — no data was changed. Re-run with --dry-run=0 to post after review.');
        }

        return self::SUCCESS;
    }

    protected function runForTenant(DepreciationService $depreciationService, int $year, int $month, bool $dryRun): void
    {
        $report = $depreciationService->runForPeriod($year, $month, $dryRun);

        if (empty($report)) {
            $this->line('No fixed assets found.');
        } else {
            $this->table(
                ['Asset ID', 'Code', 'Name', 'Branch ID', 'Status', 'Amount'],
                array_map(fn ($row) => [
                    $row['asset_id'], $row['code'], $row['name'], $row['branch_id'], $row['status'], number_format($row['amount'], 2),
                ], $report)
            );
        }

        $legacyExpenses = Expense::where('model_type', FixedAsset::class)
            ->where(function ($q) {
                $q->where('fixed_asset_entry_type', 'depreciation')->orWhereNull('fixed_asset_entry_type');
            })
            ->get(['id', 'model_id', 'amount', 'expense_date']);

        if ($legacyExpenses->isNotEmpty()) {
            $this->newLine();
            $this->comment('Legacy depreciation faked through the Expense module (not touched — see prompt 09 for repair):');
            $this->table(
                ['Expense ID', 'Fixed Asset ID', 'Amount', 'Date'],
                $legacyExpenses->map(fn ($e) => [$e->id, $e->model_id, number_format($e->amount, 2), $e->expense_date])
            );
        }
    }
}
