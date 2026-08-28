<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Enums\UserTypeEnum;
use App\Http\Resources\Tenant\CustomerResource;
use App\Models\Tenant\User;
use Illuminate\Http\Request;

class CustomersApiController extends ApiController
{
    protected function permission(): string
    {
        return 'customers.list,customers.show,customers.create,customers.update';
    }

    public function index(Request $request)
    {
        $customers = User::withCount('sales')
            ->filter([
                'type' => UserTypeEnum::CUSTOMER->value,
                'search' => $request->query('search'),
                'active' => $request->query('active', 'all'),
            ])
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($customers, CustomerResource::class);
    }

    public function show(int $id)
    {
        $customer = User::withCount('sales')->where('type', UserTypeEnum::CUSTOMER->value)->find($id);
        if (!$customer) {
            return $this->error('Not Found', 404);
        }

        return $this->success(new CustomerResource($customer));
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

        $validated['type'] = UserTypeEnum::CUSTOMER->value;
        $customer = User::create($validated);

        return $this->success(new CustomerResource($customer), 201);
    }

    public function update(Request $request, int $id)
    {
        $customer = User::where('type', UserTypeEnum::CUSTOMER->value)->find($id);
        if (!$customer) {
            return $this->error('Not Found', 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
        ]);

        $customer->update($validated);

        return $this->success(new CustomerResource($customer));
    }
}
