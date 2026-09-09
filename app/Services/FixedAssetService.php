<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Enums\CheckDirectionEnum;
use App\Enums\CheckStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\Check;
use App\Models\Tenant\FixedAsset;
use App\Models\Tenant\OrderPayment;
use App\Models\Tenant\Transaction;
use App\Repositories\FixedAssetRepository;
use Illuminate\Support\Facades\DB;

class FixedAssetService
{
    public function __construct(
        private FixedAssetRepository $repo,
        private TransactionService $transactionService,
        private DepreciationService $depreciationService
    ) {}

    public function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    public function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    public function first($id = null, $relations = [])
    {
        return $this->repo->first($relations, ['id' => $id]);
    }

    public function save($id = null, array $data): FixedAsset
    {
        return DB::transaction(function () use ($id, $data) {
            $asset = $id ? $this->repo->find($id) : null;
            $isNew = !$asset;
            $asset ??= new FixedAsset();

            $originalCost = (float) ($asset->cost ?? 0);
            $originalPaid = (float) ($asset->paid_amount ?? 0);
            $hasBeenPosted = !$isNew && $this->hasAcquisitionInvoice($asset);
            $hasDepreciation = !$isNew && (float) ($asset->accumulated_depreciation ?? 0) > 0;

            $newCost = (float) ($data['cost'] ?? 0);

            if ($hasBeenPosted && abs($newCost - $originalCost) > 0.005) {
                throw new \RuntimeException(__('general.pages.fixed_assets.cost_locked_after_posting'));
            }

            if ($newCost < $originalPaid) {
                throw new \RuntimeException(__('general.pages.fixed_assets.cost_below_paid_amount'));
            }

            if (!$isNew && $hasDepreciation) {
                foreach (['useful_life_months', 'depreciation_rate', 'salvage_value', 'depreciation_start_date'] as $lockedField) {
                    if (!array_key_exists($lockedField, $data)) {
                        continue;
                    }

                    $current = $asset->{$lockedField};
                    $incoming = $data[$lockedField];
                    $changed = $lockedField === 'depreciation_start_date'
                        ? (string) $current !== (string) $incoming
                        : (float) $current !== (float) $incoming;

                    if ($changed && empty($data['revise_estimate'])) {
                        throw new \RuntimeException(__('general.pages.fixed_assets.estimate_locked_after_depreciation'));
                    }
                }
            }

            $useful = $data['useful_life_months'] ?? ($asset->useful_life_months ?? 0);
            $rate = $data['depreciation_rate'] ?? ($asset->depreciation_rate ?? null);
            $basis = $data['depreciation_basis'] ?? ($asset->depreciation_basis ?? null);

            if ($basis === 'useful_life') {
                $rate = null;
                if ((int) $useful <= 0) {
                    throw new \RuntimeException(__('general.pages.fixed_assets.depreciation_basis_required'));
                }
            } elseif ($basis === 'rate') {
                $useful = 0;
            }

            $asset->fill([
                'created_by' => $data['created_by'] ?? ($asset->created_by ?? admin()->id ?? null),
                'branch_id' => $data['branch_id'] ?? null,
                'code' => $data['code'] ?? ($asset->code ?: FixedAsset::generateCode()),
                'name' => $data['name'],
                'purchase_date' => $data['purchase_date'] ?? null,
                'cost' => $newCost,
                'paid_amount' => $data['paid_amount'] ?? $originalPaid,
                'salvage_value' => $data['salvage_value'] ?? 0,
                'useful_life_months' => $useful,
                'depreciation_rate' => $rate,
                'depreciation_basis' => $basis,
                'depreciation_method' => $data['depreciation_method'] ?? 'straight_line',
                'depreciation_start_date' => $data['depreciation_start_date'] ?? null,
                'status' => $data['status'] ?? 'active',
                'note' => $data['note'] ?? null,
            ])->save();

            $status = $data['status'] ?? 'active';
            if (!$hasBeenPosted && $status !== FixedAsset::STATUS_UNDER_CONSTRUCTION) {
                // A non-under-construction asset must be posted to the ledger the moment it
                // exists — never left on the register unrecorded (prompt 16 item 1: FA-000002).
                $this->createPurchaseInvoice($asset, $newCost, $data['note'] ?? '');
            }

            return $asset->refresh();
        });
    }

    protected function hasAcquisitionInvoice(FixedAsset $asset): bool
    {
        return $asset->transactions()
            ->where('type', TransactionTypeEnum::FIXED_ASSETS->value)
            ->exists();
    }

    public function createPurchaseInvoice(FixedAsset $asset, float $amount, ?string $note = null): Transaction
    {
        $branchId = $asset->branch_id;
        if (!$branchId) {
            throw new \RuntimeException(__('general.pages.fixed_assets.branch_required_to_post'));
        }

        if ($amount <= 0) {
            throw new \RuntimeException(__('general.pages.fixed_assets.amount_must_be_positive'));
        }

        $fixedAssetAccount = Account::default('Fixed Asset', AccountTypeEnum::FIXED_ASSET->value, $branchId);
        $payableAccount = Account::default('Fixed Assets Payable', AccountTypeEnum::LONGTERM_LIABILITY->value, $branchId);

        return $this->transactionService->create([
            'date' => $asset->purchase_date ?? now(),
            'description' => 'Fixed Asset Invoice for #'.$asset->code.' - '.$asset->name,
            'type' => TransactionTypeEnum::FIXED_ASSETS->value,
            'reference_type' => FixedAsset::class,
            'reference_id' => $asset->id,
            'branch_id' => $branchId,
            'note' => $note ?? '',
            'amount' => $amount,
            'lines' => [
                [
                    'account_id' => $fixedAssetAccount->id,
                    'type' => 'debit',
                    'amount' => $amount,
                ],
                [
                    'account_id' => $payableAccount->id,
                    'type' => 'credit',
                    'amount' => $amount,
                ],
            ],
        ]);
    }

    public function addPayment(int $assetId, array $data, bool $reverse = false): FixedAsset
    {
        return DB::transaction(function () use ($assetId, $data, $reverse) {
            $asset = $this->repo->find($assetId);
            if (!$asset) {
                throw new \RuntimeException('Fixed asset not found');
            }

            $amount = (float) ($data['payment_amount'] ?? 0);
            if ($amount <= 0) {
                return $asset;
            }

            if (!$reverse && $amount > (float) $asset->due_amount) {
                throw new \RuntimeException('Payment amount exceeds due amount');
            }

            if ($reverse && $amount > (float) ($asset->paid_amount ?? 0)) {
                throw new \RuntimeException('Refund amount exceeds paid amount');
            }

            $branchId = $asset->branch_id;
            if (!$branchId) {
                throw new \RuntimeException('Fixed asset branch is required');
            }

            $paymentAccount = Account::assertPaymentCapable($data['payment_account'] ?? null);
            $paymentAccountId = $paymentAccount->id;
            $methodSlug = $paymentAccount->paymentMethod?->slug;

            $payableAccount = Account::default('Fixed Assets Payable', AccountTypeEnum::LONGTERM_LIABILITY->value, $branchId);

            if ($methodSlug === 'check') {
                // A fixed asset payment is a check the business ISSUES.
                $creditAccount = Account::forCheckDirection('issued', $branchId);
            } else {
                $creditAccount = $paymentAccount;
            }

            $this->transactionService->create([
                'date' => $data['payment_date'] ?? now(),
                'description' => ($reverse ? 'Refund ' : '').'Fixed Asset Payment for #'.$asset->code.' - '.$asset->name,
                'type' => TransactionTypeEnum::FIXED_ASSETS->value,
                'reference_type' => FixedAsset::class,
                'reference_id' => $asset->id,
                'branch_id' => $branchId,
                'note' => $data['payment_note'] ?? '',
                'amount' => $amount,
                'lines' => [
                    [
                        'account_id' => $creditAccount->id,
                        'type' => $reverse ? 'debit' : 'credit',
                        'amount' => $amount,
                    ],
                    [
                        'account_id' => $payableAccount->id,
                        'type' => $reverse ? 'credit' : 'debit',
                        'amount' => $amount,
                    ],
                ],
            ]);

            if (!$reverse) {
                $asset->increment('paid_amount', $amount);
            } else {
                $asset->decrement('paid_amount', $amount);
            }

            $orderPayment = OrderPayment::create([
                'payable_type' => FixedAsset::class,
                'payable_id' => $asset->id,
                'refunded' => $reverse ? 1 : 0,
                'note' => $data['payment_note'] ?? '',
                'account_id' => $paymentAccountId,
                'counterparty_account_id' => $payableAccount->id,
                'amount' => $amount,
            ]);

            if (!$reverse && $methodSlug === 'check') {
                Check::create([
                    'branch_id' => $branchId,
                    'direction' => CheckDirectionEnum::ISSUED->value,
                    'status' => CheckStatusEnum::ISSUED->value,
                    'payable_type' => FixedAsset::class,
                    'payable_id' => $asset->id,
                    'order_payment_id' => $orderPayment->id,
                    'supplier_id' => $data['supplier_id'] ?? null,
                    'amount' => $amount,
                    'check_number' => $data['check_number'] ?? null,
                    'bank_name' => $data['bank_name'] ?? null,
                    'check_date' => $data['check_date'] ?? null,
                    'due_date' => $data['due_date'] ?? null,
                    'note' => $data['payment_note'] ?? null,
                ]);
            }

            return $asset->refresh();
        });
    }

    /**
     * Prospective revision of depreciation estimates (IAS 16 §51): allowed after depreciation
     * has started, but never recalculates amounts already posted — only future charges use the
     * new figures.
     */
    public function reviseEstimate(int $assetId, array $data): FixedAsset
    {
        return $this->save($assetId, array_merge($data, ['revise_estimate' => true]));
    }

    public function dispose(int $assetId, float $proceeds, ?int $receiptAccountId = null, $date = null): FixedAsset
    {
        $asset = $this->repo->find($assetId);
        if (!$asset) {
            throw new \RuntimeException('Fixed asset not found');
        }

        return $this->depreciationService->dispose($asset, $proceeds, $receiptAccountId, $date);
    }
}
