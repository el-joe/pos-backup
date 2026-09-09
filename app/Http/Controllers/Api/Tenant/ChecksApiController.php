<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Requests\Api\Tenant\CheckActionRequest;
use App\Http\Requests\Api\Tenant\StoreCheckRequest;
use App\Http\Resources\Tenant\CheckResource;
use App\Models\Tenant\Check;
use App\Services\CheckService;
use Illuminate\Http\Request;

class ChecksApiController extends ApiController
{
    protected function permission(): string
    {
        return 'checks.list,checks.create,checks.collect,checks.clear,checks.bounce';
    }

    public function index(Request $request)
    {
        $checks = Check::query()
            ->when($request->query('direction'), fn ($q, $v) => $q->where('direction', $v))
            ->when($request->query('status'), fn ($q, $v) => $q->where('status', $v))
            ->when($request->query('branch_id'), fn ($q, $v) => $q->where('branch_id', $v))
            ->when($request->query('from_date'), fn ($q, $v) => $q->whereDate('check_date', '>=', $v))
            ->when($request->query('to_date'), fn ($q, $v) => $q->whereDate('check_date', '<=', $v))
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($checks, CheckResource::class);
    }

    public function show(int $id)
    {
        $check = Check::find($id);
        if (!$check) {
            return $this->error('Not Found', 404);
        }

        return $this->success(new CheckResource($check));
    }

    public function store(StoreCheckRequest $request)
    {
        $validated = $request->validated();
        $validated['status'] = $validated['direction'] === 'received'
            ? \App\Enums\CheckStatusEnum::UNDER_COLLECTION->value
            : \App\Enums\CheckStatusEnum::ISSUED->value;

        $check = Check::create($validated);

        return $this->success(new CheckResource($check), 201);
    }

    public function collect(CheckActionRequest $request, int $id, CheckService $checkService)
    {
        $validated = $request->validated();

        try {
            $check = $checkService->collect($id, $validated['account_id'] ?? null, $validated['note'] ?? null);
        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), 422);
        }

        return $this->success(new CheckResource($check));
    }

    public function clear(CheckActionRequest $request, int $id, CheckService $checkService)
    {
        $validated = $request->validated();

        try {
            $check = $checkService->clearIssued($id, $validated['account_id'] ?? null, $validated['note'] ?? null);
        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), 422);
        }

        return $this->success(new CheckResource($check));
    }

    public function bounce(CheckActionRequest $request, int $id, CheckService $checkService)
    {
        $validated = $request->validated();

        $check = Check::find($id);
        if (!$check) {
            return $this->error('Not Found', 404);
        }

        try {
            $check = $check->direction === \App\Enums\CheckDirectionEnum::ISSUED->value
                ? $checkService->bounceIssued($id, $validated['note'] ?? null, $validated['bank_charge'] ?? null, $validated['bank_charge_account_id'] ?? null)
                : $checkService->bounce($id, $validated['note'] ?? null, $validated['bank_charge'] ?? null, $validated['bank_charge_account_id'] ?? null);
        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), 422);
        }

        return $this->success(new CheckResource($check));
    }

    public function represent(CheckActionRequest $request, int $id, CheckService $checkService)
    {
        $validated = $request->validated();

        try {
            $check = $checkService->represent($id, $validated['replaced_by_check_id'] ?? null, $validated['note'] ?? null);
        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), 422);
        }

        return $this->success(new CheckResource($check));
    }
}
