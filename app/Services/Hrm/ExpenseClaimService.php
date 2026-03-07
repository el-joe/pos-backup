<?php

namespace App\Services\Hrm;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\ExpenseClaimLine;
use App\Repositories\Hrm\ExpenseClaimRepository;
use Illuminate\Support\Facades\DB;

class ExpenseClaimService
{
    public function __construct(private ExpenseClaimRepository $repo) {}

    public function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    public function find($id = null, $relations = [], $filter = [])
    {
        return $this->repo->find($id, $relations, $filter);
    }

    public function createWithLines(array $claimData, array $lines = [])
    {
        return DB::transaction(function () use ($claimData, $lines) {
            $claim = $this->repo->create($claimData);

            $total = 0;
            foreach ($lines as $line) {
                $amount = (float) ($line['amount'] ?? 0);
                $total += $amount;
                ExpenseClaimLine::create([
                    'expense_claim_id' => $claim->id,
                    'category_id' => $line['category_id'] ?? null,
                    'amount' => $amount,
                    'description' => $line['description'] ?? null,
                    'receipt_path' => $line['receipt_path'] ?? null,
                ]);
            }

            $claim->update(['total_amount' => $total]);

            AuditLog::log(AuditLogActionEnum::from('create_record'), ['entity' => 'Expense claim', 'id' => $claim->id]);

            return $claim->refresh();
        });
    }

    public function update($id, $data = [])
    {
        $claim = $this->repo->update($id, $data);
        AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Expense claim', 'id' => $id]);

        return $claim;
    }

    public function delete($id)
    {
        $deleted = $this->repo->delete($id);
        if ($deleted) {
            AuditLog::log(AuditLogActionEnum::from('delete_record'), ['entity' => 'Expense claim', 'id' => $id]);
        }

        return $deleted;
    }
}
