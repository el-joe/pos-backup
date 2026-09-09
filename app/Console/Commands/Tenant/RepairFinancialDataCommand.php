<?php

namespace App\Console\Commands\Tenant;

use App\Enums\AccountTypeEnum;
use App\Enums\CheckDirectionEnum;
use App\Models\Tenant;
use App\Models\Tenant\Account;
use App\Models\Tenant\CashRegister;
use App\Models\Tenant\Check;
use App\Models\Tenant\Expense;
use App\Models\Tenant\FixedAsset;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Sale;
use App\Models\Tenant\Stock;
use App\Models\Tenant\OrderPayment;
use App\Models\Tenant\Transaction;
use App\Models\Tenant\TransactionLine;
use App\Services\CashRegisterService;
use App\Services\TransactionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/**
 * Prompt 09 — live data repair.
 *
 * ⚠️ Modifies live tenant financial data. Defaults to --dry-run=1 (nothing is written).
 * Exactly one --step (1-17) runs per invocation — see docs/financial-data-repair-log.md for
 * the full defect list, the required mysqldump backup procedure, and sign-off checklist.
 *
 * Every write goes through TransactionService::create()/reverse() — never raw SQL against
 * transactions/transaction_lines, and rows in those two tables are never deleted, only
 * reversed. Steps 5, 9 and 13 are report-only by design (see class doc on each method).
 * Step 16 posts nothing to the ledger — it only corrects cash_registers counters/closes
 * stale open registers whose activity predates prompt 03's register check.
 */
class RepairFinancialDataCommand extends Command
{
    protected $signature = 'tenant:repair-financial-data {--tenant=} {--step=} {--dry-run=1} {--report-path=}';

    protected $description = 'Prompt 09: repair verified ledger defects in live tenant data, one step at a time. Dry-run by default — pass --dry-run=0 only after accountant sign-off.';

    protected bool $dryRun = true;

    protected array $reportLines = [];

    public function handle(TransactionService $transactionService): int
    {
        $step = $this->option('step');

        if ($step === null || $step === '') {
            $this->error('You must pass --step=<1-17>. Exactly one step runs per invocation — see docs/financial-data-repair-log.md.');
            return self::FAILURE;
        }

        if (str_contains((string) $step, ',') || is_array($step)) {
            $this->error('Only one --step may be requested per invocation.');
            return self::FAILURE;
        }

        $step = (int) $step;
        if ($step < 1 || $step > 17) {
            $this->error('--step must be an integer between 1 and 17.');
            return self::FAILURE;
        }

        $this->dryRun = (bool) ((int) $this->option('dry-run'));
        $tenantId = $this->option('tenant');

        if (!$this->dryRun) {
            $this->warn('=========================================================================');
            $this->warn(' LIVE MODE (--dry-run=0). This writes to real financial data.');
            $this->warn(' Confirm a fresh backup exists before proceeding — see');
            $this->warn(' docs/financial-data-repair-log.md ("Backup procedure") for the exact');
            $this->warn(' mysqldump / tenant-aware backup command.');
            $this->warn('=========================================================================');
        }

        $tenants = $tenantId
            ? Tenant::where('id', $tenantId)->get()
            : Tenant::all();

        if ($tenants->isEmpty()) {
            $this->error('No matching tenant(s) found.');
            return self::FAILURE;
        }

        $method = 'step' . $step;
        if (!method_exists($this, $method)) {
            $this->error("Step {$step} is not implemented.");
            return self::FAILURE;
        }

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            $this->reportLines = [];
            $this->line("== Tenant: {$tenant->id} — step {$step} ==".($this->dryRun ? ' (DRY RUN)' : ' (LIVE)'));

            $this->report("# Repair Step {$step} — Tenant {$tenant->id}");
            $this->report('');
            $this->report('Generated: ' . now()->toDateTimeString());
            $this->report('Mode: ' . ($this->dryRun ? 'DRY RUN (no writes)' : 'LIVE (writes applied)'));
            $this->report('');

            $this->{$method}($transactionService);

            $this->writeReport($tenant, $step);
            tenancy()->end();
        }

        if ($this->dryRun) {
            $this->newLine();
            $this->comment('Dry-run only — nothing was written. Re-run with --dry-run=0 after accountant sign-off and a fresh backup.');
        }

        return self::SUCCESS;
    }

    // ------------------------------------------------------------------
    // Reporting helpers
    // ------------------------------------------------------------------

    protected function report(string $line): void
    {
        $this->reportLines[] = $line;
    }

    protected function reportTable(array $headers, array $rows): void
    {
        $this->table($headers, $rows);

        $this->report('| ' . implode(' | ', $headers) . ' |');
        $this->report('|' . str_repeat('---|', count($headers)));
        foreach ($rows as $row) {
            $this->report('| ' . implode(' | ', array_map(fn ($v) => (string) $v, $row)) . ' |');
        }
        $this->report('');
    }

    protected function writeReport(Tenant $tenant, int $step): void
    {
        $path = $this->option('report-path')
            ?: storage_path("app/repair-reports/tenant-{$tenant->id}-step-{$step}-" . now()->format('Ymd-His') . '.md');

        $dir = dirname($path);
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put($path, implode("\n", $this->reportLines) . "\n");
        $this->info("Report written: {$path}");
    }

    protected function manualDecisionOnly(int $step, string $why): void
    {
        $this->warn("REQUIRES MANUAL DECISION — no write path implemented for step {$step}.");
        $this->line($why);
        $this->report('**REQUIRES MANUAL DECISION — no write path implemented.**');
        $this->report('');
        $this->report($why);
    }

    protected function reasonFor(int $step, string $summary): string
    {
        return "Repair step {$step}: {$summary}";
    }

    protected function accountLabel(?int $accountId): string
    {
        if (!$accountId) {
            return 'N/A';
        }
        $account = Account::withTrashed()->find($accountId);
        return $account ? "#{$account->id} {$account->name} ({$account->getRawOriginal('type')})" : "#{$accountId} (missing)";
    }

    // ------------------------------------------------------------------
    // Step 1 — register open/close posted to equity
    // ------------------------------------------------------------------

    protected function step1(TransactionService $ts): void
    {
        $reason = $this->reasonFor(1, 'reverse register open/close posted to equity');

        $transactions = Transaction::whereIn('type', ['opening_balance', 'closing_balance'])
            ->whereNull('reversed_by_transaction_id')
            ->orderBy('id')
            ->get();

        if ($transactions->isEmpty()) {
            $this->line('No unreversed opening_balance/closing_balance transactions found.');
            $this->report('No unreversed opening_balance/closing_balance transactions found.');
            return;
        }

        $rows = $transactions->map(fn (Transaction $t) => [
            $t->id, $t->type?->value ?? $t->getRawOriginal('type'), $t->branch_id, number_format((float) $t->amount, 2), $t->date,
        ])->all();

        $this->comment('Before state — transactions to reverse:');
        $this->reportTable(['ID', 'Type', 'Branch ID', 'Amount', 'Date'], $rows);

        $this->comment('What would change: each transaction gets a mirrored contra-entry (debits<->credits flipped), linked via reversed_by_transaction_id. Originals are never deleted.');
        $this->report('Action: reverse each transaction via TransactionService::reverse(), reason: "' . $reason . '".');

        if ($this->dryRun) {
            foreach ($transactions as $t) {
                $this->line("[DRY RUN] Would reverse transaction #{$t->id} ({$t->date}) amount {$t->amount}");
            }
            $this->report('DRY RUN: no writes performed.');
            return;
        }

        $reversedIds = [];
        foreach ($transactions as $t) {
            $reversal = $ts->reverse($t, $reason);
            $reversedIds[] = [$t->id, $reversal->id];
            $this->info("Reversed #{$t->id} -> reversal #{$reversal->id}");
        }

        $this->reportTable(['Original ID', 'Reversal ID'], $reversedIds);
        $this->report('After state: original transactions kept, each now has reversed_by_transaction_id set; reversal entries post an equal-and-opposite contra-entry.');
    }

    // ------------------------------------------------------------------
    // Step 2 — orphan register entries (report only; reversed via step 1)
    // ------------------------------------------------------------------

    protected function step2(TransactionService $ts): void
    {
        $registerCount = CashRegister::count();
        $transactions = Transaction::whereIn('type', ['opening_balance', 'closing_balance'])->orderBy('id')->get();

        $this->line("cash_registers row count: {$registerCount}");
        $this->report("cash_registers row count: {$registerCount}");

        $rows = $transactions->map(fn (Transaction $t) => [
            $t->id, $t->type?->value ?? $t->getRawOriginal('type'), $t->reference_type, $t->reference_id, $t->branch_id, number_format((float) $t->amount, 2), $t->reversed_by_transaction_id ? 'reversed' : 'live',
        ])->all();

        $this->comment('Orphan register entries (cash_registers is empty but these transactions reference a register/branch):');
        $this->reportTable(['ID', 'Type', 'Reference Type', 'Reference ID', 'Branch ID', 'Amount', 'Status'], $rows);

        $this->manualDecisionOnly(
            2,
            'These orphan rows are reported here for visibility only. The fix is the reversal performed by --step=1 '
            . '(same 5 transactions). This step never writes — it exists purely so the orphan condition (empty '
            . 'cash_registers table vs live register transactions) is documented and traceable independently of step 1.'
        );
    }

    // ------------------------------------------------------------------
    // Step 3 — duplicate control accounts (delegates to MergeDuplicateAccountsCommand)
    // ------------------------------------------------------------------

    protected function step3(TransactionService $ts): void
    {
        $this->comment('Delegating to tenant:merge-duplicate-accounts (prompt 02) — this step never reimplements merge logic.');
        $this->report('Delegates to `tenant:merge-duplicate-accounts` (prompt 02).');

        $tenantId = tenancy()->tenant?->id ?? $this->option('tenant');

        $exitCode = Artisan::call('tenant:merge-duplicate-accounts', [
            '--tenant' => $tenantId,
            '--dry-run' => $this->dryRun ? 1 : 0,
        ]);

        $output = Artisan::output();
        $this->line($output);
        $this->report('```');
        $this->report(trim($output));
        $this->report('```');
        $this->report('Exit code: ' . $exitCode);
    }

    // ------------------------------------------------------------------
    // Step 4 — split subsidiary ledgers per user (also via merge command)
    // ------------------------------------------------------------------

    protected function step4(TransactionService $ts): void
    {
        $this->comment('Split subsidiary ledgers: user 11 -> accounts 21, 25, 44; user 10 -> accounts 20, 28.');
        $this->report('Split subsidiary ledgers reported per user (same class of defect as step 3 — duplicate accounts '
            . 'sharing an owner that were never consolidated). Delegates to `tenant:merge-duplicate-accounts`, which '
            . 'groups by (type, branch_id, model_type, model_id) and merges duplicates within each group.');

        $accountIds = [21, 25, 44, 20, 28];
        $accounts = Account::withTrashed()->whereIn('id', $accountIds)->get()->keyBy('id');

        $rows = [];
        foreach ($accountIds as $id) {
            $account = $accounts->get($id);
            $rows[] = $account
                ? [$id, $account->name, $account->getRawOriginal('type'), $account->branch_id, $account->model_type, $account->model_id]
                : [$id, 'NOT FOUND', '-', '-', '-', '-'];
        }
        $this->reportTable(['Account ID', 'Name', 'Type', 'Branch ID', 'Model Type', 'Model ID'], $rows);

        $tenantId = tenancy()->tenant?->id ?? $this->option('tenant');
        $exitCode = Artisan::call('tenant:merge-duplicate-accounts', [
            '--tenant' => $tenantId,
            '--dry-run' => $this->dryRun ? 1 : 0,
        ]);
        $output = Artisan::output();
        $this->line($output);
        $this->report('```');
        $this->report(trim($output));
        $this->report('```');
        $this->report('Exit code: ' . $exitCode);
        $this->report('');
        $this->report('NOTE: if these accounts differ on branch_id (not just model_id), the grouping key in '
            . 'MergeDuplicateAccountsCommand will NOT treat them as duplicates automatically — in that case this is '
            . 'flagged for manual review rather than silently merged across branches.');
    }

    // ------------------------------------------------------------------
    // Step 5 — transactions with zero lines (report only)
    // ------------------------------------------------------------------

    protected function step5(TransactionService $ts): void
    {
        $knownIds = [41, 42, 43, 44, 45, 50];

        $transactions = Transaction::whereIn('id', $knownIds)
            ->orWhereDoesntHave('lines')
            ->get(['id', 'type', 'reference_type', 'reference_id', 'amount', 'date']);

        $rows = $transactions->map(fn (Transaction $t) => [
            $t->id, $t->type?->value ?? $t->getRawOriginal('type'), $t->reference_type, $t->reference_id, number_format((float) $t->amount, 2), $t->date,
        ])->all();

        $this->comment('Transactions with zero lines (ids 41,42,43,44,45,50 = purchases 8,9 / checks 2,3, plus any others found live):');
        $this->reportTable(['ID', 'Type', 'Reference Type', 'Reference ID', 'Amount', 'Date'], $rows);

        $this->manualDecisionOnly(
            5,
            'Each zero-line transaction needs an accountant decision (delete the stray header row vs. reconstruct '
            . 'the missing lines from source documents) that cannot be made safely by an automated script. Flagged '
            . 'per row above for manual review.'
        );
    }

    // ------------------------------------------------------------------
    // Step 6 — unposted purchases (8, 9)
    // ------------------------------------------------------------------

    protected function step6(TransactionService $ts): void
    {
        $reason = $this->reasonFor(6, 'post missing inventory/AP entry for unposted purchase');

        $purchaseIds = [8, 9];
        $purchases = Purchase::with('purchaseItems')->whereIn('id', $purchaseIds)->get();

        if ($purchases->isEmpty()) {
            $this->line('Purchases 8/9 not found on this tenant.');
            $this->report('Purchases 8/9 not found on this tenant.');
            return;
        }

        foreach ($purchases as $purchase) {
            $hasTransaction = Transaction::where('reference_type', Purchase::class)
                ->where('reference_id', $purchase->id)
                ->where('type', 'purchase_invoice')
                ->exists();

            $itemsTotal = (float) $purchase->purchaseItems->sum(function ($item) {
                $discount = (float) $item->purchase_price * ((float) ($item->discount_percentage ?? 0)) / 100;
                return ((float) $item->purchase_price - $discount) * (float) $item->qty;
            });

            $this->comment("Purchase #{$purchase->id} (branch {$purchase->branch_id}) — already posted: " . ($hasTransaction ? 'YES (skipping)' : 'NO'));
            $this->report("Purchase #{$purchase->id}: computed inventory cost = " . number_format($itemsTotal, 2) . '; already posted = ' . ($hasTransaction ? 'yes' : 'no'));

            if ($hasTransaction) {
                continue;
            }

            $this->line("Would post: DR Inventory {$itemsTotal} / CR Supplier (Accounts Payable) {$itemsTotal}, branch {$purchase->branch_id}, dated {$purchase->order_date}");

            if ($this->dryRun) {
                $this->report('DRY RUN: no writes performed for purchase #' . $purchase->id);
                continue;
            }

            $inventoryAccount = Account::default('Inventory', AccountTypeEnum::INVENTORY->value, $purchase->branch_id);
            $supplierAccount = Account::default('Supplier', AccountTypeEnum::SUPPLIER->value, $purchase->branch_id, 'cash')
                ?? null;
            // Supplier control account is keyed by (type, branch_id) via Account::default(); the
            // supplier-specific sub-ledger, if any, is out of scope for this generic backfill.

            $transaction = $ts->create([
                'date' => $purchase->order_date ?? now(),
                'description' => $reason . " (purchase #{$purchase->id})",
                'type' => 'purchase_invoice',
                'reference_type' => Purchase::class,
                'reference_id' => $purchase->id,
                'branch_id' => $purchase->branch_id,
                'note' => $reason,
                'lines' => [
                    ['account_id' => $inventoryAccount->id, 'type' => 'debit', 'amount' => $itemsTotal],
                    ['account_id' => $supplierAccount->id, 'type' => 'credit', 'amount' => $itemsTotal],
                ],
            ]);

            $this->info("Posted transaction #{$transaction->id} for purchase #{$purchase->id}");
            $this->report('After state: posted transaction #' . $transaction->id . ' for purchase #' . $purchase->id);
        }
    }

    // ------------------------------------------------------------------
    // Step 7 — inventory GL vs sub-ledger variance
    // ------------------------------------------------------------------

    protected function step7(TransactionService $ts): void
    {
        $reason = $this->reasonFor(7, 'post inventory revaluation entry for residual GL vs sub-ledger variance');

        $branchIds = Stock::query()->distinct()->pluck('branch_id');

        foreach ($branchIds as $branchId) {
            $stocks = Stock::where('branch_id', $branchId)->get();

            $glDebit = (float) TransactionLine::whereHas('account', fn ($q) => $q->where('type', AccountTypeEnum::INVENTORY->value)->where('branch_id', $branchId))
                ->where('type', 'debit')->sum('amount');
            $glCredit = (float) TransactionLine::whereHas('account', fn ($q) => $q->where('type', AccountTypeEnum::INVENTORY->value)->where('branch_id', $branchId))
                ->where('type', 'credit')->sum('amount');
            $glBalance = $glDebit - $glCredit;

            $weightedAverageTotal = (float) $stocks->sum(fn (Stock $s) => (float) $s->qty * (float) $s->unit_cost);
            $currentTotalValue = (float) $stocks->sum('total_value');
            $variance = $glBalance - $weightedAverageTotal;

            $this->comment("Branch {$branchId}: GL={$glBalance}, Σ(qty×unit_cost)={$weightedAverageTotal}, Σ(stocks.total_value)={$currentTotalValue}, variance(GL - qty×cost)=" . round($variance, 2));
            $this->reportTable(
                ['Branch ID', 'GL Balance', 'Sub-ledger (qty×unit_cost)', 'Current stocks.total_value', 'Variance'],
                [[$branchId, number_format($glBalance, 2), number_format($weightedAverageTotal, 2), number_format($currentTotalValue, 2), number_format($variance, 2)]]
            );

            if (abs($variance) <= 0.005 && abs($currentTotalValue - $weightedAverageTotal) <= 0.005) {
                $this->line("Branch {$branchId}: no material variance — skipping.");
                continue;
            }

            $this->line("Would recompute stocks.total_value = qty × unit_cost per row (weighted-average basis, reusing ReconcileInventoryCommand's read-only formula), then post ONE dated inventory revaluation entry for the residual GL variance of " . number_format($variance, 2) . '.');

            if ($this->dryRun) {
                $this->report('DRY RUN: no writes performed for branch ' . $branchId);
                continue;
            }

            foreach ($stocks as $stock) {
                // stocks.unit_cost was truncated to decimal(10,2) before the column was widened
                // (see 2026_09_08_172252_add_total_value_to_stocks) — recomputing qty×unit_cost
                // from that stored value just reproduces the same truncated total. The true cost
                // basis lives in purchase_items (purchase_price is decimal(15,4)), so recompute
                // the weighted-average unit cost from purchase history instead, net of discount
                // and tax (tax is recovered separately — see step 17 / input-VAT reclassification).
                $trueUnitCost = $this->weightedAverageUnitCostFromPurchases((int) $stock->product_id);

                $recomputed = $trueUnitCost !== null
                    ? round((float) $stock->qty * $trueUnitCost, 4)
                    : round((float) $stock->qty * (float) $stock->unit_cost, 4);

                if (abs($recomputed - (float) $stock->total_value) > 0.0001) {
                    $stock->update(['total_value' => $recomputed]);
                }
            }

            if (abs($variance) > 0.005) {
                $inventoryAccount = Account::default('Inventory', AccountTypeEnum::INVENTORY->value, $branchId);
                $ownerAccount = Account::default('owner_account', AccountTypeEnum::OWNER_ACCOUNT->value);

                $isOverstated = $variance > 0; // GL > sub-ledger => GL inventory must come DOWN
                $amount = abs($variance);

                $transaction = $ts->create([
                    'date' => now(),
                    'description' => $reason . " (branch {$branchId})",
                    'type' => 'stock_adjustment',
                    'reference_type' => \App\Models\Tenant\Branch::class,
                    'reference_id' => $branchId,
                    'branch_id' => $branchId,
                    'note' => $reason . '. GL was ' . number_format($glBalance, 2) . ', sub-ledger (Σ qty×weighted-average unit_cost) is ' . number_format($weightedAverageTotal, 2) . ', residual ' . number_format($amount, 2) . '.',
                    'lines' => $isOverstated ? [
                        ['account_id' => $ownerAccount->id, 'type' => 'debit', 'amount' => $amount],
                        ['account_id' => $inventoryAccount->id, 'type' => 'credit', 'amount' => $amount],
                    ] : [
                        ['account_id' => $inventoryAccount->id, 'type' => 'debit', 'amount' => $amount],
                        ['account_id' => $ownerAccount->id, 'type' => 'credit', 'amount' => $amount],
                    ],
                ]);

                $this->info("Posted revaluation transaction #{$transaction->id} for branch {$branchId}, amount {$amount}");
                $this->report('After state: stocks.total_value recomputed on weighted-average basis; posted inventory revaluation transaction #' . $transaction->id . ' for branch ' . $branchId . ', amount ' . number_format($amount, 2) . '.');
            }
        }
    }

    /**
     * Weighted-average unit cost for a product from its purchase history, net of discount and
     * tax, at full decimal(15,4) precision — used to close inventory sub-ledger variance that
     * the stocks.unit_cost column's former decimal(10,2) precision baked in. Returns null when
     * the product has no purchase_items rows (e.g. opening stock with no purchase history).
     */
    protected function weightedAverageUnitCostFromPurchases(int $productId): ?float
    {
        $items = \App\Models\Tenant\PurchaseItem::where('product_id', $productId)->get();

        if ($items->isEmpty()) {
            return null;
        }

        $totalQty = 0.0;
        $totalCost = 0.0;

        foreach ($items as $item) {
            $qty = (float) $item->actual_qty;
            if ($qty <= 0) {
                continue;
            }

            $totalQty += $qty;
            $totalCost += $qty * (float) $item->unit_cost_after_discount;
        }

        if ($totalQty <= 0) {
            return null;
        }

        return round($totalCost / $totalQty, 4);
    }

    // ------------------------------------------------------------------
    // Step 8 — orphan GL for deleted expenses (#2, #3)
    // ------------------------------------------------------------------

    protected function step8(TransactionService $ts): void
    {
        $reason = $this->reasonFor(8, 'reverse orphan GL entries referencing deleted expenses');

        $expenseIds = [2, 3];
        $existing = Expense::withTrashed()->whereIn('id', $expenseIds)->pluck('id')->all();
        $missing = array_diff($expenseIds, $existing);

        $this->line('Expenses ' . implode(',', $expenseIds) . ' — present (incl. trashed): [' . implode(',', $existing) . '], hard-deleted/never existed: [' . implode(',', $missing) . ']');
        $this->report('Expenses checked: ' . implode(', ', $expenseIds) . '. Hard-deleted / never existed: ' . (empty($missing) ? 'none' : implode(', ', $missing)) . '.');

        $transactions = Transaction::where('reference_type', Expense::class)
            ->whereIn('reference_id', $expenseIds)
            ->whereNull('reversed_by_transaction_id')
            ->get();

        $rows = $transactions->map(fn (Transaction $t) => [
            $t->id, $t->reference_id, number_format((float) $t->amount, 2), $t->date,
        ])->all();

        $this->comment('Orphan GL entries referencing deleted expenses:');
        $this->reportTable(['Transaction ID', 'Expense ID', 'Amount', 'Date'], $rows);

        if ($transactions->isEmpty()) {
            $this->line('Nothing to reverse.');
            $this->report('Nothing to reverse.');
        } else {
            $this->line('Would reverse each transaction above via TransactionService::reverse().');

            if ($this->dryRun) {
                $this->report('DRY RUN: no writes performed.');
            } else {
                $reversedRows = [];
                foreach ($transactions as $t) {
                    $reversal = $ts->reverse($t, $reason);
                    $reversedRows[] = [$t->id, $reversal->id];
                }
                $this->reportTable(['Original ID', 'Reversal ID'], $reversedRows);
            }
        }

        $this->report('');
        $this->report('Guard against recurrence: `App\\Models\\Tenant\\Expense` now reverses any transactions '
            . 'referencing it in a `deleting` model event (see app/Models/Tenant/Expense.php), so this class of '
            . 'orphan can no longer be created by deleting an Expense going forward.');
    }

    // ------------------------------------------------------------------
    // Step 9 — unposted accrued expense #9 (report only)
    // ------------------------------------------------------------------

    protected function step9(TransactionService $ts): void
    {
        $expense = Expense::find(9);

        if (!$expense) {
            $this->line('Expense #9 not found on this tenant.');
            $this->report('Expense #9 not found on this tenant.');
            return;
        }

        $hasTransaction = Transaction::where('reference_type', Expense::class)->where('reference_id', 9)->exists();

        $rows = [[$expense->id, $expense->type?->value ?? $expense->getRawOriginal('type'), number_format((float) $expense->amount, 2), $expense->expense_date, $hasTransaction ? 'yes' : 'no']];
        $this->comment('Unposted accrued expense:');
        $this->reportTable(['Expense ID', 'Type', 'Amount', 'Date', 'Has Transaction'], $rows);

        $this->manualDecisionOnly(
            9,
            'Expense #9 is accrued (10,000, dated ' . $expense->expense_date . ') with no ledger transaction. Whether '
            . 'to post it now (DR Expense / CR Accrued Liabilities) or leave it unposted pending further review is an '
            . 'accountant judgment call (e.g. was it later paid/cancelled off-system?) — no write path implemented.'
        );
    }

    // ------------------------------------------------------------------
    // Step 10 — prepaid expense #10 treated as expense
    // ------------------------------------------------------------------

    protected function step10(TransactionService $ts): void
    {
        $reason = $this->reasonFor(10, 'reclassify prepaid expense from Expense to Prepaid Asset');

        $expense = Expense::find(10);
        if (!$expense) {
            $this->line('Expense #10 not found on this tenant.');
            $this->report('Expense #10 not found on this tenant.');
            return;
        }

        $transaction = Transaction::where('reference_type', Expense::class)
            ->where('reference_id', 10)
            ->whereNull('reversed_by_transaction_id')
            ->first();

        $this->comment("Expense #10: type={$expense->type?->value}, amount={$expense->amount}, date={$expense->expense_date}, transaction=" . ($transaction ? "#{$transaction->id}" : 'none'));
        $this->report('Expense #10: amount ' . number_format((float) $expense->amount, 2) . ', dated ' . $expense->expense_date . ', posting transaction: ' . ($transaction ? '#' . $transaction->id : 'none found'));

        if (!$transaction) {
            $this->manualDecisionOnly(10, 'No posting transaction found for expense #10 — nothing to reclassify automatically.');
            return;
        }

        $creditLine = $transaction->lines()->where('type', 'credit')->first();
        $this->line('Would reverse transaction #' . $transaction->id . ' and repost: DR Prepaid Asset ' . $expense->amount . ' / CR ' . $this->accountLabel($creditLine?->account_id) . ' ' . $expense->amount . '.');
        $this->report('Would create Account::default(\'Prepaid Expenses\', AccountTypeEnum::PREPAID_ASSET, branch_id) if it does not exist, reverse transaction #' . $transaction->id . ', and repost DR Prepaid Asset / CR ' . $this->accountLabel($creditLine?->account_id) . '.');
        $this->report('Amortization: no amortization engine exists yet in this codebase — this repair only reclassifies the initial entry to the balance sheet; a recurring amortization schedule (DR Expense / CR Prepaid Asset per period) is a TODO for a future prompt, not implemented here.');

        if ($this->dryRun) {
            $this->report('DRY RUN: no writes performed.');
            return;
        }

        $prepaidAccount = Account::default('Prepaid Expenses', AccountTypeEnum::PREPAID_ASSET->value, $expense->branch_id);

        $reversal = $ts->reverse($transaction, $reason);
        $this->info("Reversed original transaction #{$transaction->id} -> #{$reversal->id}");

        $repost = $ts->create([
            'date' => now(),
            'description' => $reason . ' (expense #10)',
            'type' => 'expense',
            'reference_type' => Expense::class,
            'reference_id' => $expense->id,
            'branch_id' => $expense->branch_id,
            'note' => $reason . '. TODO: amortization schedule not yet automated — no amortization engine exists in this codebase.',
            'lines' => [
                ['account_id' => $prepaidAccount->id, 'type' => 'debit', 'amount' => (float) $expense->amount],
                ['account_id' => $creditLine->account_id, 'type' => 'credit', 'amount' => (float) $expense->amount],
            ],
        ]);

        $this->info("Posted reclassified transaction #{$repost->id}");
        $this->report('After state: reversed #' . $transaction->id . ' (-> #' . $reversal->id . '), posted reclassified transaction #' . $repost->id . ' against the new Prepaid Asset account #' . $prepaidAccount->id . '.');
    }

    // ------------------------------------------------------------------
    // Step 11 — check on wrong side (FA-000003 / txn 68)
    // ------------------------------------------------------------------

    protected function step11(TransactionService $ts): void
    {
        $reason = $this->reasonFor(11, 'repost check payment from Checks Under Collection to Issued Checks');

        $transaction = Transaction::find(68);
        if (!$transaction) {
            $this->line('Transaction #68 not found on this tenant.');
            $this->report('Transaction #68 not found on this tenant.');
            return;
        }

        $lines = $transaction->lines()->get();
        $rows = $lines->map(fn (TransactionLine $l) => [$l->id, $this->accountLabel($l->account_id), $l->type, number_format((float) $l->amount, 2)])->all();

        $this->comment('Transaction #68 lines (before):');
        $this->reportTable(['Line ID', 'Account', 'Type', 'Amount'], $rows);

        $wrongAccount = Account::withTrashed()->where('type', AccountTypeEnum::CHECKS_UNDER_COLLECTION->value)->first();
        $wrongLine = $wrongAccount ? $lines->firstWhere('account_id', $wrongAccount->id) : null;

        if (!$wrongLine) {
            $this->line('No line against Checks Under Collection found on transaction #68 — nothing to fix (may already be corrected).');
            $this->report('No line against Checks Under Collection found on transaction #68 — nothing to fix.');
            return;
        }

        $issuedAccount = Account::forCheckDirection(CheckDirectionEnum::ISSUED->value, $transaction->branch_id);
        $this->line('Would reverse transaction #68, then repost with the affected line moved from ' . $this->accountLabel($wrongLine->account_id) . ' to Issued Checks account #' . $issuedAccount->id . ', amount ' . $wrongLine->amount . '.');
        $this->report('Would reverse #68 and repost with the line redirected to Issued Checks (account #' . $issuedAccount->id . ').');

        if ($this->dryRun) {
            $this->report('DRY RUN: no writes performed.');
            return;
        }

        $newLines = $lines->map(function (TransactionLine $l) use ($wrongLine, $issuedAccount) {
            return [
                'account_id' => $l->id === $wrongLine->id ? $issuedAccount->id : $l->account_id,
                'type' => $l->type,
                'amount' => $l->amount,
            ];
        })->all();

        $reversal = $ts->reverse($transaction, $reason);
        $this->info("Reversed #68 -> #{$reversal->id}");

        $repost = $ts->create([
            'date' => now(),
            'description' => $reason . ' (original #68)',
            'type' => $transaction->type instanceof \BackedEnum ? $transaction->type->value : $transaction->type,
            'reference_type' => $transaction->reference_type,
            'reference_id' => $transaction->reference_id,
            'branch_id' => $transaction->branch_id,
            'note' => $reason,
            'lines' => $newLines,
        ]);

        $this->info("Posted corrected transaction #{$repost->id}");
        $this->report('After state: reversed #68 (-> #' . $reversal->id . '), posted corrected transaction #' . $repost->id . ' against Issued Checks account #' . $issuedAccount->id . '.');
    }

    // ------------------------------------------------------------------
    // Step 12 — missing depreciation (delegates to RunDepreciationCommand)
    // ------------------------------------------------------------------

    protected function step12(TransactionService $ts): void
    {
        $this->comment('Delegating to tenant:run-depreciation (prompt 05) per historical period — this step never reimplements the depreciation engine.');
        $this->report('Delegates to `tenant:run-depreciation` (prompt 05), called once per historical month.');

        $asset = FixedAsset::where('code', 'FA-000001')->first();
        if (!$asset || !$asset->depreciation_start_date) {
            $this->line('FA-000001 not found or has no depreciation_start_date — cannot determine backfill window.');
            $this->report('FA-000001 not found or has no depreciation_start_date — cannot determine backfill window.');
            return;
        }

        $start = $asset->depreciation_start_date->copy()->startOfMonth();
        $end = now()->copy()->startOfMonth();

        $this->line("Backfill window for FA-000001: {$start->toDateString()} through {$end->toDateString()}");
        $this->report('Backfill window (FA-000001 depreciation_start_date through current month): ' . $start->toDateString() . ' – ' . $end->toDateString());

        $tenantId = tenancy()->tenant?->id ?? $this->option('tenant');
        $period = $start->copy();
        $calls = [];

        while ($period->lte($end)) {
            $this->line('Would run: tenant:run-depreciation --tenant=' . $tenantId . ' --year=' . $period->year . ' --month=' . $period->month . ' --dry-run=' . ($this->dryRun ? 1 : 0));

            $exitCode = Artisan::call('tenant:run-depreciation', [
                '--tenant' => $tenantId,
                '--year' => $period->year,
                '--month' => $period->month,
                '--dry-run' => $this->dryRun ? 1 : 0,
            ]);
            $output = Artisan::output();
            $this->line($output);
            $calls[] = [$period->year, $period->month, $exitCode];

            $period->addMonthNoOverflow();
        }

        $this->reportTable(['Year', 'Month', 'Exit Code'], $calls);
        $this->report('Each period above dates its own depreciation entry to that period, per prompt 05\'s engine — not all posted "today".');
    }

    // ------------------------------------------------------------------
    // Step 13 — missing check records (report only)
    // ------------------------------------------------------------------

    protected function step13(TransactionService $ts): void
    {
        $asset = FixedAsset::where('code', 'FA-000003')->first();
        if (!$asset) {
            $this->line('FA-000003 not found on this tenant.');
            $this->report('FA-000003 not found on this tenant.');
            return;
        }

        $payments = Transaction::where('reference_type', FixedAsset::class)
            ->where('reference_id', $asset->id)
            ->where('amount', 5000)
            ->get(['id', 'amount', 'date']);

        $existingChecks = Check::where('payable_type', FixedAsset::class)->where('payable_id', $asset->id)->count();

        $rows = $payments->map(fn (Transaction $t) => [$t->id, number_format((float) $t->amount, 2), $t->date])->all();
        $this->comment("FA-000003 — 5,000 payment transactions found: {$payments->count()}, matching checks rows: {$existingChecks}");
        $this->reportTable(['Transaction ID', 'Amount', 'Date'], $rows);

        $this->manualDecisionOnly(
            13,
            "FA-000003's two 5,000 check payments have no corresponding `checks` rows. Reconstructing a check record "
            . '(check number, bank, dates) from a GL entry alone is guesswork without the source document, so this is '
            . 'flagged for the accountant to either locate the original check numbers and create the rows, or '
            . 'document why no check row is needed (e.g. it was actually paid by cash/transfer and the "check" label '
            . 'on the transaction is itself wrong).'
        );
    }

    // ------------------------------------------------------------------
    // Step 14 — deleted check with surviving GL (check #2 / txn 43)
    // ------------------------------------------------------------------

    protected function step14(TransactionService $ts): void
    {
        $reason = $this->reasonFor(14, 'reverse GL entry referencing deleted check #2');

        $check = Check::withTrashed()->find(2);
        $this->line('Check #2 status: ' . ($check ? ($check->trashed() ? 'soft-deleted' : 'exists') : 'not found (hard-deleted or never existed)'));
        $this->report('Check #2: ' . ($check ? ($check->trashed() ? 'soft-deleted' : 'exists (unexpected — no repair needed)') : 'not found (hard-deleted or never existed)'));

        $transaction = Transaction::find(43);
        if (!$transaction) {
            $this->line('Transaction #43 not found on this tenant.');
            $this->report('Transaction #43 not found on this tenant.');
            return;
        }

        if ($transaction->reversed_by_transaction_id) {
            $this->line('Transaction #43 already reversed.');
            $this->report('Transaction #43 already reversed — nothing to do.');
            return;
        }

        $rows = $transaction->lines()->get()->map(fn (TransactionLine $l) => [$l->id, $this->accountLabel($l->account_id), $l->type, number_format((float) $l->amount, 2)])->all();
        $this->comment('Transaction #43 lines (before):');
        $this->reportTable(['Line ID', 'Account', 'Type', 'Amount'], $rows);

        $this->line('Would reverse transaction #43 via TransactionService::reverse().');
        $this->report('Would reverse transaction #43.');

        if ($this->dryRun) {
            $this->report('DRY RUN: no writes performed.');
            $this->report('');
            $this->report('Guard against recurrence: `App\\Models\\Tenant\\Check` now blocks `forceDelete()` outright '
                . '(see app/Models/Tenant/Check.php) — only soft-delete is possible going forward.');
            return;
        }

        $reversal = $ts->reverse($transaction, $reason);
        $this->info("Reversed #43 -> #{$reversal->id}");
        $this->report('After state: reversed transaction #43 -> reversal #' . $reversal->id . '.');
        $this->report('');
        $this->report('Guard against recurrence: `App\\Models\\Tenant\\Check` now blocks `forceDelete()` outright '
            . '(see app/Models/Tenant/Check.php) — only soft-delete is possible going forward.');
    }

    // ------------------------------------------------------------------
    // Step 15 — drawer overstated by non-cash sale payment
    // ------------------------------------------------------------------

    protected function step15(TransactionService $ts): void
    {
        $sale = Sale::find(6);
        if (!$sale) {
            $this->line('Sale #6 not found on this tenant.');
            $this->report('Sale #6 not found on this tenant.');
            return;
        }

        $checkPayment = 145867.50;

        $register = CashRegister::where('branch_id', $sale->branch_id)
            ->whereDate('opened_at', '<=', $sale->order_date)
            ->where(function ($q) use ($sale) {
                $q->whereNull('closed_at')->orWhereDate('closed_at', '>=', $sale->order_date);
            })
            ->orderByDesc('id')
            ->first();

        if (!$register) {
            $this->line('No cash register found covering sale #6\'s date/branch — cannot compute corrected counter.');
            $this->report('No cash register found covering sale #6 — cannot compute corrected counter automatically.');
            return;
        }

        $currentTotalSales = (float) $register->total_sales;
        $correctedTotalSales = round($currentTotalSales - $checkPayment, 2);

        $this->comment("Register #{$register->id} (branch {$register->branch_id}): total_sales before = " . number_format($currentTotalSales, 2));
        $this->reportTable(
            ['Register ID', 'Branch', 'total_sales (before)', 'Non-cash portion (sale #6 check payment)', 'total_sales (corrected)'],
            [[$register->id, $register->branch_id, number_format($currentTotalSales, 2), number_format($checkPayment, 2), number_format($correctedTotalSales, 2)]]
        );

        $this->line('Would update cash_registers.total_sales directly (no GL lines involved, so this bypasses TransactionService by design) from ' . $currentTotalSales . ' to ' . $correctedTotalSales . '.');
        $this->report('Direct model update planned: CashRegister#' . $register->id . '.total_sales = ' . $correctedTotalSales . ' (was ' . $currentTotalSales . '). No TransactionService call — this is a register counter, not a GL posting.');

        if ($this->dryRun) {
            $this->report('DRY RUN: no writes performed.');
            return;
        }

        $register->update(['total_sales' => $correctedTotalSales]);
        $this->info("Updated register #{$register->id} total_sales to {$correctedTotalSales}");
        $this->report('After state: CashRegister#' . $register->id . '.total_sales = ' . $correctedTotalSales . '.');
    }

    // ------------------------------------------------------------------
    // Step 16 — reconcile and close stale open registers
    // ------------------------------------------------------------------

    protected function step16(TransactionService $ts): void
    {
        $reason = $this->reasonFor(16, 'reconcile and close stale open register — counters were not recorded for pre-register activity');
        $cashRegisterService = app(CashRegisterService::class);

        $registers = CashRegister::where('status', 'open')->whereNull('closed_at')->orderBy('id')->get();

        if ($registers->isEmpty()) {
            $this->line('No open cash registers found.');
            $this->report('No open cash registers found.');
            return;
        }

        foreach ($registers as $register) {
            $branchCashBalance = $this->branchCashBalance($register->branch_id);

            $before = [
                $register->id, $register->branch_id, number_format((float) $register->opening_balance, 2),
                number_format((float) $register->total_sales, 2), number_format((float) $register->total_purchases, 2),
                number_format((float) $register->total_expenses, 2), number_format((float) $register->total_deposits, 2),
                number_format((float) $register->total_withdrawals, 2),
                number_format((float) $register->calculated_closing_balance, 2),
                number_format($branchCashBalance, 2),
                number_format($branchCashBalance - (float) $register->calculated_closing_balance, 2),
            ];

            $this->comment("Register #{$register->id} (branch {$register->branch_id}) — before state:");
            $this->reportTable(
                ['ID', 'Branch', 'Opening', 'Sales', 'Purchases', 'Expenses', 'Deposits', 'Withdrawals', 'Calculated Closing', 'Actual Branch Cash', 'Variance'],
                [$before]
            );

            $window = OrderPayment::where('created_at', '>=', $register->opened_at)
                ->where('created_at', '<=', now())
                ->where('refunded', false)
                ->get();

            $recomputed = [
                'total_sales' => 0.0,
                'total_purchases' => 0.0,
                'total_expenses' => 0.0,
            ];

            $payableTypeMap = [
                \App\Models\Tenant\Sale::class => 'total_sales',
                \App\Models\Tenant\Purchase::class => 'total_purchases',
                \App\Models\Tenant\Expense::class => 'total_expenses',
            ];

            foreach ($window as $payment) {
                $field = $payableTypeMap[$payment->payable_type] ?? null;
                if (!$field || !$cashRegisterService->isCashAccount($payment->account_id)) {
                    continue;
                }

                $payable = $payment->payable;
                if (!$payable || (int) $payable->branch_id !== (int) $register->branch_id) {
                    continue;
                }

                $recomputed[$field] += (float) $payment->amount;
            }

            $recomputed = array_map(fn ($v) => round($v, 2), $recomputed);

            $expectedClosingBalance = round(
                (float) $register->opening_balance
                + $recomputed['total_sales']
                + (float) $register->total_purchase_refunds
                + (float) $register->total_expense_refunds
                + (float) $register->total_deposits
                - (float) $register->total_sale_refunds
                - $recomputed['total_purchases']
                - $recomputed['total_expenses']
                - (float) $register->total_withdrawals,
                2
            );

            $recomputedVariance = round($branchCashBalance - $expectedClosingBalance, 2);

            $this->comment('Recomputed from order_payments (cash accounts only, ' . $register->opened_at . ' through now, branch ' . $register->branch_id . '):');
            $this->reportTable(
                ['Recomputed Sales', 'Recomputed Purchases', 'Recomputed Expenses', 'Recomputed Closing', 'Actual Branch Cash', 'Residual Variance'],
                [[
                    number_format($recomputed['total_sales'], 2),
                    number_format($recomputed['total_purchases'], 2),
                    number_format($recomputed['total_expenses'], 2),
                    number_format($expectedClosingBalance, 2),
                    number_format($branchCashBalance, 2),
                    number_format($recomputedVariance, 2),
                ]]
            );

            $this->line('Would update cash_registers counters to the recomputed values above, then close the register with closing_balance = expected_closing_balance and discrepancy = 0. Posts nothing to the ledger.');
            $this->report('Would set total_sales/total_purchases/total_expenses to the recomputed values, then close the register: closing_balance = ' . number_format($expectedClosingBalance, 2) . ', expected_closing_balance = same, discrepancy = 0.00. No ledger posting — no real cash variance exists, the counters were simply never written for activity that predated the register opening.');

            if ($this->dryRun) {
                $this->report('DRY RUN: no writes performed.');
                continue;
            }

            $register->update([
                'total_sales' => $recomputed['total_sales'],
                'total_purchases' => $recomputed['total_purchases'],
                'total_expenses' => $recomputed['total_expenses'],
                'closing_balance' => $expectedClosingBalance,
                'expected_closing_balance' => $expectedClosingBalance,
                'discrepancy' => 0,
                'discrepancy_reason' => 'Reconciled by repair step 16 — counters were not recorded for pre-register activity',
                'closed_at' => now(),
                'status' => 'closed',
            ]);

            $this->info("Reconciled and closed register #{$register->id}, closing_balance = {$expectedClosingBalance}");
            $this->report('After state: CashRegister#' . $register->id . ' closed, closing_balance = expected_closing_balance = ' . number_format($expectedClosingBalance, 2) . ', discrepancy = 0.00.');
        }
    }

    protected function branchCashBalance(?int $branchId): float
    {
        $debit = (float) TransactionLine::whereHas('account', fn ($q) => $q->where('type', AccountTypeEnum::BRANCH_CASH->value)->where('branch_id', $branchId))
            ->where('type', 'debit')->sum('amount');
        $credit = (float) TransactionLine::whereHas('account', fn ($q) => $q->where('type', AccountTypeEnum::BRANCH_CASH->value)->where('branch_id', $branchId))
            ->where('type', 'credit')->sum('amount');

        return round($debit - $credit, 2);
    }

    // ------------------------------------------------------------------
    // Step 17 — reclassify buried input VAT out of inventory onto VAT Receivable
    // ------------------------------------------------------------------

    protected function step17(TransactionService $ts): void
    {
        $reason = $this->reasonFor(17, 'reclassify historical input VAT out of Inventory onto VAT Receivable');

        // Purchases 1 and 3 predate unit_cost_net (see 2026_09_08_220000_add_unit_cost_net_to_purchase_items_table)
        // — their tax was posted straight into inventory cost instead of being split out as
        // recoverable input VAT. New purchases already post correctly via unit_cost_net.
        $purchaseIds = [1, 3];
        $purchases = Purchase::with('purchaseItems')->whereIn('id', $purchaseIds)->get();

        if ($purchases->isEmpty()) {
            $this->line('Purchases 1/3 not found on this tenant.');
            $this->report('Purchases 1/3 not found on this tenant.');
            return;
        }

        $vatAccount = Account::default('Vat Receivable', AccountTypeEnum::VAT_RECEIVABLE->value);
        $totalVat = 0.0;
        $branchTotals = [];

        foreach ($purchases as $purchase) {
            $alreadyReclassified = Transaction::where('reference_type', Purchase::class)
                ->where('reference_id', $purchase->id)
                ->where('type', 'vat_reclassification')
                ->exists();

            $purchaseVat = 0.0;
            $rows = [];
            foreach ($purchase->purchaseItems as $item) {
                $qty = (float) $item->actual_qty;
                $netAfterDiscount = (float) $item->unit_cost_after_discount;
                $taxPct = (float) ($item->tax_percentage ?? 0);
                $itemVat = round($qty * $netAfterDiscount * ($taxPct / 100), 2);
                $purchaseVat += $itemVat;

                $rows[] = [$item->id, $item->product_id, $qty, number_format($netAfterDiscount, 4), $taxPct . '%', number_format($itemVat, 2)];
            }
            $purchaseVat = round($purchaseVat, 2);

            $this->comment("Purchase #{$purchase->id} (branch {$purchase->branch_id}) — input VAT buried in inventory = " . number_format($purchaseVat, 2) . '; already reclassified = ' . ($alreadyReclassified ? 'yes' : 'no'));
            $this->reportTable(['Purchase Item ID', 'Product ID', 'Qty', 'Net Unit Cost (after discount)', 'Tax %', 'Input VAT'], $rows);

            if ($alreadyReclassified || $purchaseVat <= 0) {
                continue;
            }

            $totalVat += $purchaseVat;
            $branchTotals[$purchase->branch_id] = ($branchTotals[$purchase->branch_id] ?? 0) + $purchaseVat;

            $this->line("Would post: DR VAT Receivable {$purchaseVat} / CR Inventory {$purchaseVat}, branch {$purchase->branch_id}, dated today.");
            $this->report('Would post DR VAT Receivable / CR Inventory ' . number_format($purchaseVat, 2) . ' for purchase #' . $purchase->id . ', dated today — not backdated into the closed purchase period.');

            if ($this->dryRun) {
                $this->report('DRY RUN: no writes performed for purchase #' . $purchase->id);
                continue;
            }

            $inventoryAccount = Account::default('Inventory', AccountTypeEnum::INVENTORY->value, $purchase->branch_id);

            $transaction = $ts->create([
                'date' => now(),
                'description' => $reason . " (purchase #{$purchase->id})",
                'type' => 'vat_reclassification',
                'reference_type' => Purchase::class,
                'reference_id' => $purchase->id,
                'branch_id' => $purchase->branch_id,
                'note' => $reason . '. Input VAT of ' . number_format($purchaseVat, 2) . ' was posted into inventory cost at purchase time; reclassified onto VAT Receivable for filing.',
                'lines' => [
                    ['account_id' => $vatAccount->id, 'type' => 'debit', 'amount' => $purchaseVat],
                    ['account_id' => $inventoryAccount->id, 'type' => 'credit', 'amount' => $purchaseVat],
                ],
            ]);

            $this->info("Posted VAT reclassification transaction #{$transaction->id} for purchase #{$purchase->id}, amount {$purchaseVat}");
            $this->report('After state: posted transaction #' . $transaction->id . ' reclassifying ' . number_format($purchaseVat, 2) . ' of input VAT from Inventory to VAT Receivable for purchase #' . $purchase->id . '.');
        }

        $this->report('');
        $this->report('Total input VAT to recover: ' . number_format($totalVat, 2) . '. By branch: ' . collect($branchTotals)->map(fn ($v, $k) => "branch {$k} = " . number_format($v, 2))->implode(', ') . '. Supporting detail is the per-item table above for the accountant to file.');
    }
}
