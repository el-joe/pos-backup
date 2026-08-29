<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Resources\Tenant\CheckResource;
use App\Models\Tenant\Check;
use Illuminate\Http\Request;

class ChecksApiController extends ApiController
{
    protected function permission(): string
    {
        return 'checks.list';
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
}
