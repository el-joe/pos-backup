<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Enums\UserTypeEnum;
use App\Http\Resources\Tenant\CustomerResource;
use App\Models\Tenant\User;
use Illuminate\Http\Request;

class SuppliersApiController extends ApiController
{
    protected function permission(): string
    {
        return 'suppliers.list,suppliers.show,suppliers.create,suppliers.update';
    }

    public function index(Request $request)
    {
        $suppliers = User::withCount('purchases')
            ->filter([
                'type' => UserTypeEnum::SUPPLIER->value,
                'search' => $request->query('search'),
                'active' => $request->query('active', 'all'),
            ])
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($suppliers, CustomerResource::class);
    }

    public function show(int $id)
    {
        $supplier = User::withCount('purchases')->where('type', UserTypeEnum::SUPPLIER->value)->find($id);
        if (!$supplier) {
            return $this->error('Not Found', 404);
        }

        return $this->success(new CustomerResource($supplier));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
        ]);

        $validated['type'] = UserTypeEnum::SUPPLIER->value;
        $supplier = User::create($validated);

        return $this->success(new CustomerResource($supplier), 201);
    }

    public function update(Request $request, int $id)
    {
        $supplier = User::where('type', UserTypeEnum::SUPPLIER->value)->find($id);
        if (!$supplier) {
            return $this->error('Not Found', 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
        ]);

        $supplier->update($validated);

        return $this->success(new CustomerResource($supplier));
    }
}
