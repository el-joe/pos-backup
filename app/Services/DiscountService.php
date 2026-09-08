<?php

namespace App\Services;

use App\Models\Tenant\Discount;
use App\Repositories\DiscountRepository;

class DiscountService
{
    public function __construct(private DiscountRepository $repo) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function find($id, $relations = [], $filter = [])
    {
        return $this->repo->find($id, $relations, $filter);
    }

    function delete($id)
    {
        return $this->repo->delete($id);
    }

    function saveHistory(Discount $discount,$sale) {
        $discount->history()->create([
            'target_type' => get_class($sale),
            'target_id' => $sale->id
        ]);
        return $discount;
    }

    /**
     * Confirms the discount is active, within its date window, and has not exhausted its usage_limit.
     * Throws if any check fails, so a discount can never be attached to a sale outside its eligibility window.
     */
    function assertEligible(Discount $discount): void {
        if (!$discount->active) {
            throw new \RuntimeException("Discount [{$discount->code}] is not active.");
        }

        $today = date('Y-m-d');
        if ($discount->start_date && $today < $discount->start_date) {
            throw new \RuntimeException("Discount [{$discount->code}] is not yet valid.");
        }
        if ($discount->end_date && $today > $discount->end_date) {
            throw new \RuntimeException("Discount [{$discount->code}] has expired.");
        }

        if ($discount->usage_limit) {
            $usedCount = $discount->history()->count();
            if ($usedCount >= $discount->usage_limit) {
                throw new \RuntimeException("Discount [{$discount->code}] has reached its usage limit.");
            }
        }
    }

    function save($id = null,$data) {
        if($id) {
            $discount = $this->repo->find($id);
            if($discount) {
                $discount->update($data);
                return $discount;
            }
        }

        return $this->repo->create($data);
    }
}
