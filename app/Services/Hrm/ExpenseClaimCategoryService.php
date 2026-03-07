<?php

namespace App\Services\Hrm;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Repositories\Hrm\ExpenseClaimCategoryRepository;

class ExpenseClaimCategoryService
{
    public function __construct(private ExpenseClaimCategoryRepository $repo) {}

    public function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    public function find($id = null, $relations = [], $filter = [])
    {
        return $this->repo->find($id, $relations, $filter);
    }

    public function create($data = [])
    {
        $category = $this->repo->create($data);
        AuditLog::log(AuditLogActionEnum::from('create_record'), ['entity' => 'Claim category', 'id' => $category->id]);
        return $category;
    }

    public function update($id, $data = [])
    {
        $category = $this->repo->update($id, $data);
        AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Claim category', 'id' => $id]);
        return $category;
    }

    public function delete($id)
    {
        $deleted = $this->repo->delete($id);
        if ($deleted) {
            AuditLog::log(AuditLogActionEnum::from('delete_record'), ['entity' => 'Claim category', 'id' => $id]);
        }
        return $deleted;
    }
}
