<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\FixedAsset;
use App\Models\Tenant\FixedAssetDepreciationEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DepreciationService
{
    public function __construct(private TransactionService $transactionService) {}

    /**
     * Reject invalid depreciation configuration: conflicting basis (both useful_life_months
     * and depreciation_rate set) and non-positive cost.
     */
    public function assertValid(FixedAsset $asset): void
    {
        if ((float) ($asset->cost ?? 0) <= 0) {
            throw new \InvalidArgumentException(__('general.messages.depreciation_invalid_cost', ['code' => $asset->code]));
        }

        if ($asset->hasConflictingDepreciationBasis()) {
            throw new \InvalidArgumentException(__('general.messages.depreciation_conflicting_basis', ['code' => $asset->code]));
        }
    }

    /**
     * Monthly depreciation charge for the asset under its configured method, capped so
     * accumulated depreciation never exceeds cost - salvage_value.
     */
    public function monthlyCharge(FixedAsset $asset): float
    {
        $this->assertValid($asset);

        if ($asset->is_disposed || $asset->is_under_construction) {
            return 0.0;
        }

        $depreciableBase = $asset->depreciable_base;
        $remaining = max(0.0, $depreciableBase - (float) $asset->accumulated_depreciation);
        if ($remaining <= 0) {
            return 0.0;
        }

        $method = (string) ($asset->depreciation_method ?? FixedAsset::METHOD_STRAIGHT_LINE);

        if ($method === FixedAsset::METHOD_STRAIGHT_LINE) {
            $lifeMonths = max(0, (int) ($asset->useful_life_months ?? 0));
            if ($lifeMonths <= 0) {
                return 0.0;
            }
            $charge = $depreciableBase / $lifeMonths;
        } else {
            // Reducing balance: the rate is applied to the current carrying value (cost - accumulated).
            $carryingValue = (float) $asset->cost - (float) $asset->accumulated_depreciation;
            $annualRate = (float) ($asset->depreciation_rate ?? 0);
            if ($annualRate <= 0) {
                $lifeMonths = max(1, (int) ($asset->useful_life_months ?? 0));
                $annualRate = 100 / max(1.0, $lifeMonths / 12);
            }
            if ($method === FixedAsset::METHOD_DOUBLE_DECLINING_BALANCE) {
                $annualRate *= 2;
            }
            $monthlyRate = max(0.0, $annualRate / 100 / 12);
            $charge = $carryingValue * $monthlyRate;
        }

        return round(min($remaining, max(0.0, $charge)), 2);
    }

    /**
     * Idempotent per asset per period — skips an asset already depreciated for the period,
     * a disposed/under-construction asset, or one that has reached its depreciable base.
     * With $dryRun (default), nothing is posted; the report shows what would happen.
     *
     * @return array<int, array{asset_id:int,code:?string,name:?string,branch_id:?int,period:string,status:string,amount:float}>
     */
    public function runForPeriod(int $year, int $month, bool $dryRun = true): array
    {
        $period = sprintf('%04d-%02d', $year, $month);
        $report = [];

        $assets = FixedAsset::query()
            ->whereNotNull('branch_id')
            ->whereNull('disposed_at')
            ->whereNotIn('status', [FixedAsset::STATUS_DISPOSED, FixedAsset::STATUS_SOLD])
            ->get();

        foreach ($assets as $asset) {
            $row = [
                'asset_id' => $asset->id,
                'code' => $asset->code,
                'name' => $asset->name,
                'branch_id' => $asset->branch_id,
                'period' => $period,
                'status' => 'skipped',
                'amount' => 0.0,
            ];

            try {
                $this->assertValid($asset);
            } catch (\InvalidArgumentException $e) {
                $row['status'] = 'invalid: '.$e->getMessage();
                $report[] = $row;
                continue;
            }

            if ($asset->is_under_construction) {
                $row['status'] = 'skipped: under construction';
                $report[] = $row;
                continue;
            }

            if ($this->alreadyDepreciatedForPeriod($asset, $year, $month)) {
                $row['status'] = 'skipped: already posted for period';
                $report[] = $row;
                continue;
            }

            if ($asset->is_fully_depreciated) {
                $row['status'] = 'skipped: fully depreciated';
                $report[] = $row;
                continue;
            }

            $charge = $this->monthlyCharge($asset);
            if ($charge <= 0) {
                $row['status'] = 'skipped: zero charge';
                $report[] = $row;
                continue;
            }

            $row['amount'] = $charge;

            if ($dryRun) {
                $row['status'] = 'would post';
                $report[] = $row;
                continue;
            }

            $this->postDepreciation($asset, $year, $month, $charge);
            $row['status'] = 'posted';
            $report[] = $row;
        }

        return $report;
    }

    protected function alreadyDepreciatedForPeriod(FixedAsset $asset, int $year, int $month): bool
    {
        if ($asset->last_depreciated_on) {
            $last = $asset->last_depreciated_on;
            if ((int) $last->year === $year && (int) $last->month === $month) {
                return true;
            }
        }

        return FixedAssetDepreciationEntry::where('fixed_asset_id', $asset->id)
            ->where('period_year', $year)
            ->where('period_month', $month)
            ->exists();
    }

    protected function postDepreciation(FixedAsset $asset, int $year, int $month, float $charge): void
    {
        DB::transaction(function () use ($asset, $year, $month, $charge) {
            $branchId = $asset->branch_id;

            $depreciationExpenseAccount = Account::default('Depreciation Expense', AccountTypeEnum::DEPRECIATION_EXPENSE->value, $branchId);
            $accumulatedDepreciationAccount = Account::default('Accumulated Depreciation', AccountTypeEnum::ACCUMULATED_DEPRECIATION->value, $branchId);

            $periodDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $transaction = $this->transactionService->create([
                'date' => $periodDate,
                'description' => 'Depreciation for #'.$asset->code.' - '.$asset->name.' ('.sprintf('%04d-%02d', $year, $month).')',
                'type' => TransactionTypeEnum::DEPRECIATION->value,
                'reference_type' => FixedAsset::class,
                'reference_id' => $asset->id,
                'branch_id' => $branchId,
                'amount' => $charge,
                'lines' => [
                    [
                        'account_id' => $depreciationExpenseAccount->id,
                        'type' => 'debit',
                        'amount' => $charge,
                    ],
                    [
                        'account_id' => $accumulatedDepreciationAccount->id,
                        'type' => 'credit',
                        'amount' => $charge,
                    ],
                ],
            ]);

            $newAccumulated = round((float) $asset->accumulated_depreciation + $charge, 2);

            $asset->update([
                'accumulated_depreciation' => $newAccumulated,
                'last_depreciated_on' => $periodDate,
            ]);

            FixedAssetDepreciationEntry::create([
                'fixed_asset_id' => $asset->id,
                'branch_id' => $branchId,
                'transaction_id' => $transaction->id,
                'period_year' => $year,
                'period_month' => $month,
                'amount' => $charge,
                'accumulated_depreciation_after' => $newAccumulated,
            ]);
        });
    }

    /**
     * Dispose an asset: DR Cash/Receivable (proceeds) + DR Accumulated Depreciation,
     * CR Fixed Asset at cost, and the balancing gain/loss to Gain/Loss on Disposal.
     */
    public function dispose(FixedAsset $asset, float $proceeds, ?int $receiptAccountId = null, $date = null): FixedAsset
    {
        if ($asset->is_disposed) {
            throw new \RuntimeException(__('general.messages.asset_already_disposed', ['code' => $asset->code]));
        }

        return DB::transaction(function () use ($asset, $proceeds, $receiptAccountId, $date) {
            $branchId = $asset->branch_id;
            $cost = (float) $asset->cost;
            $accumulated = (float) $asset->accumulated_depreciation;
            $netBookValue = max(0.0, $cost - $accumulated);
            $gainLoss = round($proceeds - $netBookValue, 2);

            $fixedAssetAccount = Account::default('Fixed Asset', AccountTypeEnum::FIXED_ASSET->value, $branchId);
            $accumulatedDepreciationAccount = Account::default('Accumulated Depreciation', AccountTypeEnum::ACCUMULATED_DEPRECIATION->value, $branchId);
            $gainLossAccount = Account::default('Gain/Loss on Disposal', AccountTypeEnum::GAIN_LOSS_ON_DISPOSAL->value, $branchId);

            $lines = [
                [
                    'account_id' => $accumulatedDepreciationAccount->id,
                    'type' => 'debit',
                    'amount' => $accumulated,
                ],
                [
                    'account_id' => $fixedAssetAccount->id,
                    'type' => 'credit',
                    'amount' => $cost,
                ],
            ];

            if ($proceeds > 0) {
                $receiptAccount = $receiptAccountId
                    ? Account::findOrFail($receiptAccountId)
                    : Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $branchId);

                $lines[] = [
                    'account_id' => $receiptAccount->id,
                    'type' => 'debit',
                    'amount' => $proceeds,
                ];
            }

            if (abs($gainLoss) > 0.005) {
                $lines[] = [
                    'account_id' => $gainLossAccount->id,
                    'type' => $gainLoss > 0 ? 'credit' : 'debit',
                    'amount' => abs($gainLoss),
                ];
            }

            $this->transactionService->create([
                'date' => $date ?? now(),
                'description' => 'Disposal of #'.$asset->code.' - '.$asset->name,
                'type' => TransactionTypeEnum::ASSET_DISPOSAL->value,
                'reference_type' => FixedAsset::class,
                'reference_id' => $asset->id,
                'branch_id' => $branchId,
                'amount' => $cost,
                'lines' => $lines,
            ]);

            $asset->update([
                'status' => FixedAsset::STATUS_DISPOSED,
                'disposed_at' => $date ?? now(),
                'disposal_proceeds' => $proceeds,
            ]);

            return $asset->refresh();
        });
    }
}
