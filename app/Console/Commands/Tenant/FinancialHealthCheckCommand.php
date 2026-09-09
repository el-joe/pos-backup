<?php

namespace App\Console\Commands\Tenant;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant;
use App\Models\Tenant\Account;
use App\Models\Tenant\CashRegister;
use App\Models\Tenant\Contracting\ChartOfAccount;
use App\Models\Tenant\FixedAsset;
use App\Models\Tenant\OrderPayment;
use App\Models\Tenant\Stock;
use App\Models\Tenant\Transaction;
use App\Models\Tenant\TransactionLine;
use App\Services\CashRegisterService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        $this->checkChartOfAccountsSeeded();
        $this->checkAccountTypesResolveToCoa();
        $this->checkUnbalanced();
        $this->checkZeroLines();
        $this->checkOrphanLines();
        $this->checkDuplicateControlAccounts();
        $this->checkDuplicateSubsidiaryAccounts();
        $this->checkInventoryVariance();
        $this->checkAssetAccountsCreditBalance();
        $this->checkConflictingDepreciationBasis();
        $this->checkMissingDepreciation();
        $this->checkOrphanReferences();
        $this->checkCashPaymentsWithoutOpenRegister();
        $this->checkTimezoneConsistency();
    }

    /**
     * config('app.timezone') is what every Carbon/Eloquent datetime cast in this app
     * assumes it's working in. MySQL's TIMESTAMP columns (opened_at, created_at, etc.)
     * convert on read/write using the connection's session time_zone, which defaults to
     * MySQL's SYSTEM zone unless 'timezone' is set on the connection in
     * config/database.php. If those two disagree, every TIMESTAMP column round-trips
     * through a silent offset — harmless for relative comparisons done entirely in PHP
     * or entirely in SQL, but it corrupts anything that mixes the two (e.g. a period
     * boundary computed in SQL and compared against a Carbon date in PHP), which is
     * exactly the shape of the monthly depreciation period logic.
     */
    protected function checkTimezoneConsistency(): void
    {
        $appTimezone = config('app.timezone');

        $row = DB::selectOne('select NOW() as db_now, UTC_TIMESTAMP() as db_utc_now, @@session.time_zone as session_tz');
        $dbNow = \Illuminate\Support\Carbon::parse($row->db_now);
        $dbUtcNow = \Illuminate\Support\Carbon::parse($row->db_utc_now);
        $driftSeconds = $dbNow->diffInSeconds($dbUtcNow, false);

        $isDrifted = $appTimezone !== 'UTC' ? false : abs($driftSeconds) > 60;
        // app.timezone=UTC is what every Carbon cast in this app assumes; if MySQL's
        // session clock disagrees with UTC by more than a minute, TIMESTAMP columns are
        // silently shifted relative to what the app believes it wrote/read.

        $this->flag('Timezone drift between app.timezone and MySQL session time_zone', $isDrifted ? 1 : 0);
        if ($isDrifted) {
            $this->table(
                ['app.timezone', 'MySQL session.time_zone', 'MySQL NOW()', 'MySQL UTC_TIMESTAMP()', 'Drift (s)'],
                [[$appTimezone, $row->session_tz, $row->db_now, $row->db_utc_now, $driftSeconds]]
            );
            $this->warn('Set the "timezone" key on the mysql connection in config/database.php to "+00:00" (or set MySQL\'s session time_zone) so TIMESTAMP columns stop round-tripping through a silent offset.');
        }
    }

    protected function checkChartOfAccountsSeeded(): void
    {
        $count = ChartOfAccount::query()->count();
        $this->flag('Empty chart_of_accounts (journal projection disabled for this tenant)', $count === 0 ? 1 : 0);
    }

    /**
     * Every accounts.type actually in use (referenced by a transaction_lines row, i.e. it
     * matters to real postings) must resolve through tenant.account_coa_map to an active
     * chart_of_accounts row. This is the check that would have caught prompt 19's gap
     * before deploy — a missing/unmapped/inactive code here means the ledger bridge will
     * either throw (bridge_strict) or silently skip the journal projection for that type.
     */
    protected function checkAccountTypesResolveToCoa(): void
    {
        if (ChartOfAccount::query()->doesntExist()) {
            // already flagged by checkChartOfAccountsSeeded — avoid double-counting noise
            return;
        }

        $map = config('tenant.account_coa_map', []);
        $activeCodes = ChartOfAccount::where('is_active', 1)->pluck('code')->all();

        $accountIdsInUse = TransactionLine::query()->distinct()->pluck('account_id');

        $typesInUse = Account::whereIn('id', $accountIdsInUse)
            ->get(['type'])
            ->pluck('type')
            ->map(fn ($t) => $t instanceof \BackedEnum ? $t->value : $t)
            ->unique();

        $offenders = [];
        foreach ($typesInUse as $type) {
            $code = $map[$type] ?? null;
            if (!$code) {
                $offenders[] = [$type, 'unmapped in account_coa_map'];
            } elseif (!in_array($code, $activeCodes, true)) {
                $offenders[] = [$type, "mapped to '{$code}' but no active chart_of_accounts row"];
            }
        }

        $this->flag('Account types in use that do not resolve to an active COA row', count($offenders));
        if (!empty($offenders)) {
            $this->table(['Account Type', 'Issue'], $offenders);
        }
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

    /**
     * Control accounts are branch-owned (model_type = Branch) — exactly one per
     * (type, branch_id) is the goal, so more than one is a real defect (e.g. the
     * issued_checks duplication fixed by Blocker B). Subsidiary accounts (one per
     * customer/supplier, model_type = User) are excluded here: grouping those by
     * (type, branch_id) alone flags every tenant with more than one customer or
     * supplier as "duplicate" — that's the goal state, not a defect. See
     * checkDuplicateSubsidiaryAccounts() for the check that actually applies to them.
     */
    protected function checkDuplicateControlAccounts(): void
    {
        $groups = Account::query()
            ->where('model_type', \App\Models\Tenant\Branch::class)
            ->get()
            ->groupBy(function (Account $account) {
                return implode('|', [
                    $account->type?->value ?? $account->getRawOriginal('type'),
                    $account->branch_id ?? 'null',
                ]);
            })
            ->filter(fn ($g) => $g->count() > 1);

        $this->flag('Duplicate control-account groups (branch-owned)', $groups->count());
        if ($groups->isNotEmpty()) {
            $rows = [];
            foreach ($groups as $key => $group) {
                $rows[] = [$key, $group->count(), $group->pluck('id')->implode(',')];
            }
            $this->table(['Type|Branch', 'Count', 'Account IDs'], $rows);
        }
    }

    /**
     * The real defect on the subsidiary side: more than one account for the same party
     * (model_type = User) of the same type — e.g. a customer that ended up with two
     * separate receivable accounts, splitting their statement across two ledgers. This
     * is NOT caught by checkDuplicateControlAccounts() above, and must never be "fixed"
     * by widening that check's grouping to include model_id — see the class doc warning
     * on tenant:merge-duplicate-accounts about collapsing subsidiary ledgers.
     */
    protected function checkDuplicateSubsidiaryAccounts(): void
    {
        $groups = Account::query()
            ->where('model_type', \App\Models\Tenant\User::class)
            ->get()
            ->groupBy(function (Account $account) {
                return implode('|', [
                    $account->model_id ?? 'null',
                    $account->type?->value ?? $account->getRawOriginal('type'),
                ]);
            })
            ->filter(fn ($g) => $g->count() > 1);

        $this->flag('Duplicate subsidiary accounts (per party)', $groups->count());
        if ($groups->isNotEmpty()) {
            $rows = [];
            foreach ($groups as $key => $group) {
                $rows[] = [$key, $group->count(), $group->pluck('id')->implode(',')];
            }
            $this->table(['PartyID|Type', 'Count', 'Account IDs'], $rows);
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

    /**
     * hasConflictingDepreciationBasis() (both useful_life_months and depreciation_rate set)
     * is enforced by DepreciationService::assertValid() before anything is posted — but this
     * command's "expected accumulated" figure below calls
     * FixedAsset::calculateAccumulatedDepreciation() directly on the model, which does not
     * consult depreciation_basis or call assertValid() at all. It silently falls back to the
     * useful_life_months (straight-line) basis whenever that column is set, regardless of
     * whether depreciation_rate is also set — so a contradictory asset like FA-000001 (36
     * months AND a 10% rate) never surfaced as a problem here even though run-depreciation
     * would refuse to post it. Flag the contradiction on its own, and exclude those assets
     * from the "expected accumulated" estimate below since that number is meaningless (and
     * silently one-sided) until an accountant picks a basis.
     */
    protected function checkConflictingDepreciationBasis(): void
    {
        $assets = FixedAsset::query()
            ->whereNotNull('depreciation_start_date')
            ->whereNotIn('status', [FixedAsset::STATUS_UNDER_CONSTRUCTION])
            ->get()
            ->filter(fn (FixedAsset $a) => $a->hasConflictingDepreciationBasis());

        $this->flag('Assets with contradictory depreciation basis (both useful_life_months and rate set)', $assets->count());
        if ($assets->isNotEmpty()) {
            $this->table(
                ['Asset ID', 'Code', 'Name', 'Useful Life (months)', 'Rate (%)', 'depreciation_basis'],
                $assets->map(fn (FixedAsset $a) => [$a->id, $a->code, $a->name, $a->useful_life_months, $a->depreciation_rate, $a->depreciation_basis ?? '— unresolved —'])
            );
            $this->warn('Do not guess which basis was intended — ask the accountant, then set depreciation_basis explicitly. tenant:run-depreciation already refuses to post these.');
        }
    }

    protected function checkMissingDepreciation(): void
    {
        $assets = FixedAsset::query()
            ->whereNotNull('depreciation_start_date')
            ->where('depreciation_start_date', '<=', now())
            ->whereNotIn('status', [FixedAsset::STATUS_UNDER_CONSTRUCTION])
            ->get()
            ->reject(fn (FixedAsset $a) => $a->hasConflictingDepreciationBasis());

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

    /**
     * Sales/purchases/expenses can still be paid in cash while no register is open for the
     * branch (nothing blocks it yet — see prompt 21) — the drawer counters just silently
     * don't count that payment. This flags it read-only rather than blocking checkout, so a
     * missing register shows up here instead of surfacing as an unexplained drawer variance
     * at close time.
     *
     * Caveat this check cannot resolve on its own: order_payments.account_id currently
     * stores the counterparty (customer/supplier) account for Sales/Purchases, not the
     * tender account — see the prompt 11 defect (fixed going forward by
     * counterparty_account_id, but the historical backfill onto the correct account_id
     * hasn't run yet). Until that backfill runs, isCashAccount($payment->account_id) can
     * only tell us whether the counterparty account happens to be flagged as cash, not
     * whether the payment itself was cash — so a row below may not actually be a cash
     * payment. Re-run this check after the backfill and treat it as provisional until then.
     */
    protected function checkCashPaymentsWithoutOpenRegister(): void
    {
        $cashRegisterService = app(CashRegisterService::class);

        $registers = CashRegister::query()->get(['id', 'branch_id', 'opened_at', 'closed_at']);
        $payments = OrderPayment::where('refunded', false)->with('payable')->get();

        $offenders = [];
        foreach ($payments as $payment) {
            if (!$cashRegisterService->isCashAccount($payment->account_id)) {
                continue;
            }

            $payable = $payment->payable;
            if (!$payable || !isset($payable->branch_id)) {
                continue;
            }

            $coveringRegister = $registers->first(function (CashRegister $register) use ($payable, $payment) {
                return (int) $register->branch_id === (int) $payable->branch_id
                    && $register->opened_at <= $payment->created_at
                    && ($register->closed_at === null || $register->closed_at >= $payment->created_at);
            });

            if (!$coveringRegister) {
                // Nearest register for the same branch, purely to make the gap legible in the
                // report — format both timestamps through the same Carbon path (no mixing raw
                // DB strings with cast Carbon instances) so the comparison isn't misleading.
                $nearestRegister = $registers
                    ->where('branch_id', $payable->branch_id)
                    ->sortBy(fn (CashRegister $r) => abs($r->opened_at->diffInSeconds($payment->created_at)))
                    ->first();

                $offenders[] = [
                    $payment->id,
                    class_basename($payment->payable_type),
                    $payment->payable_id,
                    $payable->branch_id,
                    number_format((float) $payment->amount, 2),
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $nearestRegister ? $nearestRegister->opened_at->format('Y-m-d H:i:s') : '— none open for this branch —',
                ];
            }
        }

        $this->flag('Cash payments recorded with no open register covering them', count($offenders));
        if (!empty($offenders)) {
            $this->table(['Order Payment ID', 'Payable Type', 'Payable ID', 'Branch ID', 'Amount', 'Paid At', 'Nearest Register opened_at'], $offenders);
            $this->comment('Note: account_id on Sales/Purchases still stores the counterparty (prompt 11), so "cash" above is provisional — see class doc on this method.');
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
