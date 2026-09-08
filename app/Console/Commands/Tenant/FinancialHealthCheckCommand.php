<?php

namespace App\Console\Commands\Tenant;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant;
use App\Models\Tenant\Account;
use App\Models\Tenant\FixedAsset;
use App\Models\Tenant\Stock;
use App\Models\Tenant\Transaction;
use App\Models\Tenant\TransactionLine;
use Illuminate\Console\Command;

/**
 * Read-only ledger health check, safe to schedule daily. Never writes anything.
 * Exit code is non-zero if any issue is found, for cron/alerting.
 */
class FinancialHealthCheckCommand extends Command
{
    protected $signature = 'tenant:financial-health-check {--tenant=}';

    protected $description = 'Read-only daily health check for ledger integrity. Exits non-zero if any issue is found (for cron alerting).';

    protected bool $hasIssues = false;

    public function handle(): int
    {
        $tenantId = $this->option('tenant');

        $tenants = $tenantId
            ? Tenant::where('id', $tenantId)->get()
            : Tenant::all();

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            $this->info("Tenant: {$tenant->id}");
            $this->checkTenant();
            tenancy()->end();
        }

        if ($this->hasIssues) {
            $this->newLine();
            $this->error('One or more issues found — see above.');
            return self::FAILURE;
        }

        $this->newLine();
        $this->info('No issues found.');
        return self::SUCCESS;
    }

    protected function flag(string $label, int $count): void
    {
        if ($count > 0) {
            $this->hasIssues = true;
        }
        $this->line("{$label}: {$count}" . ($count > 0 ? ' ⚠' : ''));
    }

    protected function checkTenant(): void
    {
        $this->checkUnbalanced();
        $this->checkZeroLines();
        $this->checkOrphanLines();
        $this->checkDuplicateControlAccounts();
        $this->checkInventoryVariance();
        $this->checkAssetAccountsCreditBalance();
        $this->checkMissingDepreciation();
        $this->checkOrphanReferences();
    }

    protected function checkUnbalanced(): void
    {
        $rows = Transaction::unbalanced()->get(['id', 'type', 'amount', 'date']);
        $this->flag('Unbalanced transactions', $rows->count());
        if ($rows->isNotEmpty()) {
            $this->table(['ID', 'Type', 'Amount', 'Date'], $rows->map(fn ($t) => [$t->id, $t->type?->value ?? $t->getRawOriginal('type'), $t->amount, $t->date]));
        }
    }

    protected function checkZeroLines(): void
    {
        $rows = Transaction::whereDoesntHave('lines')->get(['id', 'type', 'reference_type', 'reference_id', 'date']);
        $this->flag('Transactions with zero lines', $rows->count());
        if ($rows->isNotEmpty()) {
            $this->table(['ID', 'Type', 'Reference Type', 'Reference ID', 'Date'], $rows->map(fn ($t) => [$t->id, $t->type?->value ?? $t->getRawOriginal('type'), $t->reference_type, $t->reference_id, $t->date]));
        }
    }

    protected function checkOrphanLines(): void
    {
        $orphanTransaction = TransactionLine::whereDoesntHave('transaction')->count();
        $orphanAccount = TransactionLine::whereDoesntHave('account')->count();

        $this->flag('Orphan lines (transaction deleted)', $orphanTransaction);
        $this->flag('Orphan lines (account missing, incl. hard-deleted)', $orphanAccount);
    }

    protected function checkDuplicateControlAccounts(): void
    {
        $groups = Account::query()
            ->get()
            ->groupBy(function (Account $account) {
                return implode('|', [
                    $account->type?->value ?? $account->getRawOriginal('type'),
                    $account->branch_id ?? 'null',
                ]);
            })
            ->filter(fn ($g) => $g->count() > 1);

        $this->flag('Duplicate control-account groups (type, branch_id)', $groups->count());
        if ($groups->isNotEmpty()) {
            $rows = [];
            foreach ($groups as $key => $group) {
                $rows[] = [$key, $group->count(), $group->pluck('id')->implode(',')];
            }
            $this->table(['Type|Branch', 'Count', 'Account IDs'], $rows);
        }
    }

    protected function checkInventoryVariance(): void
    {
        $branchIds = Stock::query()->distinct()->pluck('branch_id');
        $variantBranches = [];

        foreach ($branchIds as $branchId) {
            $stocks = Stock::where('branch_id', $branchId)->get();
            $subLedger = (float) $stocks->sum(fn ($s) => (float) $s->qty * (float) $s->unit_cost);

            $glDebit = (float) TransactionLine::whereHas('account', fn ($q) => $q->where('type', AccountTypeEnum::INVENTORY->value)->where('branch_id', $branchId))
                ->where('type', 'debit')->sum('amount');
            $glCredit = (float) TransactionLine::whereHas('account', fn ($q) => $q->where('type', AccountTypeEnum::INVENTORY->value)->where('branch_id', $branchId))
                ->where('type', 'credit')->sum('amount');
            $glBalance = $glDebit - $glCredit;

            $variance = $glBalance - $subLedger;
            if (abs($variance) > 0.005) {
                $variantBranches[] = [$branchId, number_format($glBalance, 2), number_format($subLedger, 2), number_format($variance, 2)];
            }
        }

        $this->flag('Branches with inventory GL vs sub-ledger variance', count($variantBranches));
        if (!empty($variantBranches)) {
            $this->table(['Branch ID', 'GL Balance', 'Sub-ledger (qty×unit_cost)', 'Variance'], $variantBranches);
        }
    }

    protected function checkAssetAccountsCreditBalance(): void
    {
        $assetTypes = [
            AccountTypeEnum::FIXED_ASSET->value,
            AccountTypeEnum::INVENTORY->value,
            AccountTypeEnum::BRANCH_CASH->value,
            AccountTypeEnum::CHECKS_UNDER_COLLECTION->value,
            AccountTypeEnum::PREPAID_ASSET->value,
        ];

        $accounts = Account::whereIn('type', $assetTypes)->get();
        $offenders = [];

        foreach ($accounts as $account) {
            $debit = (float) TransactionLine::where('account_id', $account->id)->where('type', 'debit')->sum('amount');
            $credit = (float) TransactionLine::where('account_id', $account->id)->where('type', 'credit')->sum('amount');
            $balance = $debit - $credit;
            if ($balance < -0.005) {
                $offenders[] = [$account->id, $account->name, $account->getRawOriginal('type'), number_format($balance, 2)];
            }
        }

        $this->flag('Asset control accounts holding a credit balance', count($offenders));
        if (!empty($offenders)) {
            $this->table(['Account ID', 'Name', 'Type', 'Balance'], $offenders);
        }
    }

    protected function checkMissingDepreciation(): void
    {
        $assets = FixedAsset::query()
            ->whereNotNull('depreciation_start_date')
            ->where('depreciation_start_date', '<=', now())
            ->whereNotIn('status', [FixedAsset::STATUS_UNDER_CONSTRUCTION])
            ->get();

        $offenders = [];
        foreach ($assets as $asset) {
            if ((float) $asset->accumulated_depreciation <= 0 && $asset->calculateAccumulatedDepreciation(now()) > 0.005) {
                $offenders[] = [$asset->id, $asset->code, $asset->name, $asset->depreciation_start_date, number_format($asset->calculateAccumulatedDepreciation(now()), 2)];
            }
        }

        $this->flag('Assets past depreciation start with none posted', count($offenders));
        if (!empty($offenders)) {
            $this->table(['Asset ID', 'Code', 'Name', 'Depreciation Start', 'Expected Accumulated'], $offenders);
        }
    }

    protected function checkOrphanReferences(): void
    {
        $referenceTypes = Transaction::query()
            ->whereNotNull('reference_type')
            ->distinct()
            ->pluck('reference_type');

        $orphanCount = 0;
        $rows = [];

        foreach ($referenceTypes as $type) {
            if (!class_exists($type)) {
                continue;
            }

            $transactions = Transaction::where('reference_type', $type)->whereNotNull('reference_id')->get(['id', 'reference_id']);
            if ($transactions->isEmpty()) {
                continue;
            }

            $ids = $transactions->pluck('reference_id')->unique();

            $model = new $type();
            $usesSoftDeletes = in_array(\Illuminate\Database\Eloquent\SoftDeletingScope::class, array_map('get_class', $model->getGlobalScopes() ?? []), true)
                || method_exists($model, 'trashed');

            $existingIds = $usesSoftDeletes
                ? $type::withTrashed()->whereIn($model->getKeyName(), $ids)->pluck($model->getKeyName())
                : $type::whereIn($model->getKeyName(), $ids)->pluck($model->getKeyName());

            $missingIds = $ids->diff($existingIds);
            foreach ($missingIds as $missingId) {
                $orphanCount++;
                $affected = $transactions->where('reference_id', $missingId)->pluck('id')->implode(',');
                $rows[] = [$type, $missingId, $affected];
            }
        }

        $this->flag('GL entries referencing deleted parent rows (reference_type/reference_id)', $orphanCount);
        if (!empty($rows)) {
            $this->table(['Reference Type', 'Reference ID', 'Transaction IDs'], $rows);
        }
    }
}
