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
use App\Repositories\FixedAssetRepository;
use Illuminate\Support\Facades\DB;

class FixedAssetService
{
    public function __construct(private FixedAssetRepository $repo, private TransactionService $transactionService) {}

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
            $asset = $id ? $this->repo->find($id) : new FixedAsset();
            if (!$asset) {
                $asset = new FixedAsset();
            }

            $asset->fill([
                'created_by' => $data['created_by'] ?? (admin()->id ?? null),
                'branch_id' => $data['branch_id'] ?? null,
                'code' => $data['code'],
                'name' => $data['name'],
                'purchase_date' => $data['purchase_date'] ?? null,
                'cost' => $data['cost'] ?? 0,
                'paid_amount' => $data['paid_amount'] ?? ($asset->paid_amount ?? 0),
                'salvage_value' => $data['salvage_value'] ?? 0,
                'useful_life_months' => $data['useful_life_months'] ?? 0,
                'depreciation_rate' => $data['depreciation_rate'] ?? null,
                'depreciation_method' => $data['depreciation_method'] ?? 'straight_line',
                'depreciation_start_date' => $data['depreciation_start_date'] ?? null,
                'status' => $data['status'] ?? 'active',
                'note' => $data['note'] ?? null,
            ])->save();

            return $asset->refresh();
        });
    }

    public function createPurchaseInvoice(FixedAsset $asset, float $amount, ?string $note = null): void
    {
        $branchId = $asset->branch_id;
        if (!$branchId || $amount <= 0) {
            return;
        }

        $fixedAssetAccount = Account::default('Fixed Asset', AccountTypeEnum::FIXED_ASSET->value, $branchId);
        $payableAccount = Account::default('Fixed Assets Payable', AccountTypeEnum::LONGTERM_LIABILITY->value, $branchId);

        $this->transactionService->create([
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
        $asset = $this->repo->find($assetId);
        if (!$asset) {
            throw new \RuntimeException('Fixed asset not found');
        }

        $amount = (float)($data['payment_amount'] ?? 0);
        if ($amount <= 0) {
            return $asset;
        }

        if (!$reverse && $amount > (float)$asset->due_amount) {
            throw new \RuntimeException('Payment amount exceeds due amount');
        }

        if ($reverse && $amount > (float)($asset->paid_amount ?? 0)) {
            throw new \RuntimeException('Refund amount exceeds paid amount');
        }

        $branchId = $asset->branch_id;
        if (!$branchId) {
            throw new \RuntimeException('Fixed asset branch is required');
        }

        $paymentAccountId = (int)($data['payment_account'] ?? 0);
        if ($paymentAccountId <= 0) {
            throw new \RuntimeException('Payment account is required');
        }

        $paymentAccount = Account::with('paymentMethod')->find($paymentAccountId);
        if (!$paymentAccount) {
            throw new \RuntimeException('Payment account not found');
        }
        $methodSlug = $paymentAccount?->paymentMethod?->slug;

        $payableAccount = Account::default('Fixed Assets Payable', AccountTypeEnum::LONGTERM_LIABILITY->value, $branchId);

        if ($methodSlug === 'check') {
            $creditAccount = Account::default('Issued Checks', AccountTypeEnum::ISSUED_CHECKS->value, $branchId);
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
                'amount' => $amount,
                'check_number' => $data['check_number'] ?? null,
                'bank_name' => $data['bank_name'] ?? null,
                'check_date' => $data['check_date'] ?? null,
                'due_date' => $data['due_date'] ?? null,
                'note' => $data['payment_note'] ?? null,
            ]);
        }

        return $asset->refresh();
    }
}
