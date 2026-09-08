<?php

namespace App\Console\Commands\Tenant;

use App\Models\Tenant;
use App\Models\Tenant\Account;
use App\Models\Tenant\Check;
use App\Models\Tenant\OrderPayment;
use App\Models\Tenant\TransactionLine;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MergeDuplicateAccountsCommand extends Command
{
    protected $signature = 'tenant:merge-duplicate-accounts {--tenant=} {--dry-run=1}';
    protected $description = 'Merge duplicate accounts sharing the same (type, branch_id, model_type, model_id) into one survivor. Dry-run by default.';

    public function handle(): int
    {
        $tenantId = $this->option('tenant');
        $dryRun = (bool) ((int) $this->option('dry-run'));

        $tenants = $tenantId
            ? Tenant::where('id', $tenantId)->get()
            : Tenant::all();

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            $this->info("Tenant: {$tenant->id}".($dryRun ? ' (dry-run)' : ' (LIVE MERGE)'));
            $this->processTenant($dryRun);
            tenancy()->end();
        }

        if ($dryRun) {
            $this->newLine();
            $this->comment('Dry-run only — no data was changed. Re-run with --dry-run=0 to apply after review.');
        }

        return self::SUCCESS;
    }

    protected function processTenant(bool $dryRun): void
    {
        $groups = Account::query()
            ->get()
            ->groupBy(function (Account $account) {
                return implode('|', [
                    $account->type?->value ?? $account->getRawOriginal('type'),
                    $account->branch_id ?? 'null',
                    $account->model_type ?? 'null',
                    $account->model_id ?? 'null',
                ]);
            })
            ->filter(fn ($group) => $group->count() > 1);

        if ($groups->isEmpty()) {
            $this->line('No duplicate account groups found.');
            return;
        }

        foreach ($groups as $key => $group) {
            $sorted = $group->sortBy('id')->values();
            $survivor = $sorted->first();
            $duplicates = $sorted->slice(1);

            $this->line("Group [{$key}] — survivor: #{$survivor->id} ({$survivor->name})");

            $groupTable = [];
            $survivorBalanceBefore = $this->accountBalance($survivor->id);
            $combinedBalanceBefore = $survivorBalanceBefore;

            foreach ($duplicates as $duplicate) {
                $lineCount = TransactionLine::where('account_id', $duplicate->id)->count();
                $orderPaymentCount = OrderPayment::where('account_id', $duplicate->id)->count();
                $collectedCheckCount = Check::where('collected_account_id', $duplicate->id)->count();
                $clearedCheckCount = Check::where('cleared_account_id', $duplicate->id)->count();

                $duplicateBalance = $this->accountBalance($duplicate->id);
                $combinedBalanceBefore += $duplicateBalance;

                $groupTable[] = [
                    $duplicate->id,
                    $duplicate->name,
                    $lineCount,
                    $orderPaymentCount,
                    $collectedCheckCount,
                    $clearedCheckCount,
                    number_format($duplicateBalance, 2),
                ];
            }

            $this->table(
                ['Duplicate ID', 'Name', 'Transaction Lines', 'Order Payments', 'Checks Collected', 'Checks Cleared', 'Balance'],
                $groupTable
            );

            if (!$dryRun) {
                DB::transaction(function () use ($survivor, $duplicates) {
                    foreach ($duplicates as $duplicate) {
                        TransactionLine::where('account_id', $duplicate->id)->update(['account_id' => $survivor->id]);
                        OrderPayment::where('account_id', $duplicate->id)->update(['account_id' => $survivor->id]);
                        Check::where('collected_account_id', $duplicate->id)->update(['collected_account_id' => $survivor->id]);
                        Check::where('cleared_account_id', $duplicate->id)->update(['cleared_account_id' => $survivor->id]);

                        $duplicate->delete(); // soft delete only — never hard-delete
                    }
                });

                $survivorBalanceAfter = $this->accountBalance($survivor->id);
                $this->table(
                    ['Metric', 'Value'],
                    [
                        ['Survivor balance before', number_format($survivorBalanceBefore, 2)],
                        ['Duplicates combined balance before', number_format($combinedBalanceBefore - $survivorBalanceBefore, 2)],
                        ['Expected survivor balance after', number_format($combinedBalanceBefore, 2)],
                        ['Actual survivor balance after', number_format($survivorBalanceAfter, 2)],
                        ['Match', abs($survivorBalanceAfter - $combinedBalanceBefore) <= 0.005 ? 'YES' : 'MISMATCH'],
                    ]
                );
                $this->info("Merged ".$duplicates->count()." duplicate(s) into #{$survivor->id}.");
            }
        }
    }

    protected function accountBalance(int $accountId): float
    {
        $debit = (float) TransactionLine::where('account_id', $accountId)->where('type', 'debit')->sum('amount');
        $credit = (float) TransactionLine::where('account_id', $accountId)->where('type', 'credit')->sum('amount');

        return $debit - $credit;
    }
}
