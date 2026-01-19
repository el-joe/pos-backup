<?php

namespace App\Services;

use App\Models\Tenant\FixedAsset;
use App\Repositories\FixedAssetRepository;
use Illuminate\Support\Facades\DB;

class FixedAssetService
{
    public function __construct(private FixedAssetRepository $repo) {}

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
                'salvage_value' => $data['salvage_value'] ?? 0,
                'useful_life_months' => $data['useful_life_months'] ?? 0,
                'depreciation_method' => $data['depreciation_method'] ?? 'straight_line',
                'depreciation_start_date' => $data['depreciation_start_date'] ?? null,
                'status' => $data['status'] ?? 'active',
                'note' => $data['note'] ?? null,
            ])->save();

            return $asset->refresh();
        });
    }
}
