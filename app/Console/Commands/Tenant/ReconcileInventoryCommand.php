<?php

namespace App\Console\Commands\Tenant;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant;
use App\Models\Tenant\Stock;
use App\Models\Tenant\TransactionLine;
use Illuminate\Console\Command;

class ReconcileInventoryCommand extends Command
{
    protected $signature = 'tenant:reconcile-inventory {--tenant=} {--branch=}';
    protected $description = 'Read-only reconciliation of GL inventory balance vs stock valuation, per product and per branch';

    public function handle(): int
    {
        $tenantId = $this->option('tenant');

        $tenants = $tenantId
            ? Tenant::where('id', $tenantId)->get()
            : Tenant::all();

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            $this->info("Tenant: {$tenant->id}");
            $this->reconcileTenant();
            tenancy()->end();
        }

        return self::SUCCESS;
    }

    protected function reconcileTenant(): void
    {
        $branchIds = Stock::query()
            ->when($this->option('branch'), fn($q) => $q->where('branch_id', $this->option('branch')))
            ->distinct()
            ->pluck('branch_id');

        if ($branchIds->isEmpty()) {
            $this->line('No stock rows found.');
            return;
        }

        $branchRows = [];

        foreach ($branchIds as $branchId) {
            $glBalance = $this->glInventoryBalance($branchId);

            $stocks = Stock::where('branch_id', $branchId)->get();
            $qtyTimesUnitCost = (float) $stocks->sum(fn($s) => (float) $s->qty * (float) $s->unit_cost);
            $totalValueSum = (float) $stocks->sum('total_value');

            $branchRows[] = [
                $branchId,
                number_format($glBalance, 4),
                number_format($qtyTimesUnitCost, 4),
                number_format($totalValueSum, 4),
                number_format($glBalance - $totalValueSum, 4),
            ];
        }

        $this->comment('Per-branch: GL Inventory balance vs Σ(qty×unit_cost) vs Σ(total_value)');
        $this->table(
            ['Branch ID', 'GL Inventory Balance', 'Σ(qty×unit_cost)', 'Σ(total_value)', 'Variance (GL - total_value)'],
            $branchRows
        );

        $productRows = [];
        $products = Stock::query()
            ->when($this->option('branch'), fn($q) => $q->where('branch_id', $this->option('branch')))
            ->with(['product'])
            ->get();

        foreach ($products as $stock) {
            $qtyTimesUnitCost = (float) $stock->qty * (float) $stock->unit_cost;
            $drift = $qtyTimesUnitCost - (float) $stock->total_value;

            if (abs($drift) > 0.005) {
                $productRows[] = [
                    $stock->product_id,
                    $stock->product?->name ?? 'N/A',
                    $stock->branch_id,
                    (float) $stock->qty,
                    number_format($stock->unit_cost, 4),
                    number_format($qtyTimesUnitCost, 4),
                    number_format($stock->total_value, 4),
                    number_format($drift, 4),
                ];
            }
        }

        if (!empty($productRows)) {
            $this->newLine();
            $this->comment('Per-product rows where qty×unit_cost drifts from total_value (rounding/legacy data):');
            $this->table(
                ['Product ID', 'Name', 'Branch ID', 'Qty', 'Unit Cost', 'Qty×Unit Cost', 'Total Value', 'Drift'],
                $productRows
            );
        } else {
            $this->newLine();
            $this->line('No per-product qty×unit_cost / total_value drift found.');
        }
    }

    protected function glInventoryBalance($branchId): float
    {
        $debit = (float) TransactionLine::whereHas('account', function ($q) use ($branchId) {
                $q->where('type', AccountTypeEnum::INVENTORY->value)->where('branch_id', $branchId);
            })
            ->where('type', 'debit')
            ->sum('amount');

        $credit = (float) TransactionLine::whereHas('account', function ($q) use ($branchId) {
                $q->where('type', AccountTypeEnum::INVENTORY->value)->where('branch_id', $branchId);
            })
            ->where('type', 'credit')
            ->sum('amount');

        return $debit - $credit;
    }
}
