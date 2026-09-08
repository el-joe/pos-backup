<?php

namespace App\Console\Commands\Tenant;

use App\Models\Tenant;
use App\Services\ExpenseService;
use Illuminate\Console\Command;

class AmortisePrepaidExpensesCommand extends Command
{
    protected $signature = 'tenant:amortise-prepaid {--tenant=} {--year=} {--month=} {--dry-run=1}';
    protected $description = 'Post monthly amortisation for prepaid expenses. Idempotent per expense per period. Dry-run by default.';

    public function handle(ExpenseService $expenseService): int
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
            $this->runForTenant($expenseService, $year, $month, $dryRun);
            tenancy()->end();
        }

        if ($dryRun) {
            $this->newLine();
            $this->comment('Dry-run only — no data was changed. Re-run with --dry-run=0 to post after review.');
        }

        return self::SUCCESS;
    }

    protected function runForTenant(ExpenseService $expenseService, int $year, int $month, bool $dryRun): void
    {
        $report = $expenseService->amortise($year, $month, $dryRun);

        if (empty($report)) {
            $this->line('No prepaid expenses with an amortisation schedule found.');
            return;
        }

        $this->table(
            ['Expense ID', 'Branch ID', 'Period', 'Status', 'Amount'],
            array_map(fn ($row) => [
                $row['expense_id'], $row['branch_id'], $row['period'], $row['status'], number_format($row['amount'], 2),
            ], $report)
        );
    }
}
