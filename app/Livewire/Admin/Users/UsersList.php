<?php

namespace App\Livewire\Admin\Users;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Sale;
use App\Services\UserService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use LivewireOperations, WithPagination;
    private $userService;
    public $current;

    #[Url]
    public $type;

    public $export;
    public $collapseFilters = false;
    public $filters = [];

    function boot() {
        $this->userService = app(UserService::class);
    }

    function setCurrent($id) {
        $this->current = $this->userService->find($id);
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        AuditLog::log(AuditLogActionEnum::DELETE_USER_TRY, ['id' => $id,'type' => $this->type]);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this user', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'User not found');
            return;
        }

        $id = $this->current->id;

        $this->userService->delete($id);

        AuditLog::log(AuditLogActionEnum::DELETE_USER, ['id' => $id,'type' => $this->type]);
        $this->popup('success', 'User deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    #[On('re-render')]
    public function render()
    {
        if ($this->export == 'excel') {
        $users = $this->userService->list(orderByDesc: 'id',filter : ['type' => $this->type , ...$this->filters]);

            $data = $users->map(function ($user, $loop) {
                #	Name	Email	Phone	Address	Sales Threshold	Active
                return [
                    'loop' => $loop + 1,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'address' => $user->address,
                    'sales_threshold' => $user->sales_threshold,
                    'active' => $user->active ? 'Active' : 'Inactive',
                ];
            })->toArray();
            $columns = ['loop', 'name', 'phone', 'email', 'address', 'sales_threshold', 'active'];
            $headers = ['#', 'Name', 'Phone', 'Email', 'Address', 'Sales Threshold', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'users');

            AuditLog::log(AuditLogActionEnum::EXPORT_USERS, ['url' => $fullPath,'type' => $this->type]);

            $this->redirectToDownload($fullPath);
        }

        $users = $this->userService->list(perPage : 10 , orderByDesc: 'id',filter : ['type' => $this->type , ...$this->filters]);

        $dueTotals = [];
        if (in_array($this->type, ['customer', 'supplier'], true)) {
            $userIds = $users->pluck('id')->filter()->values();

            if ($userIds->isNotEmpty() && $this->type === 'customer') {
                $sales = Sale::with(['saleItems'])
                    ->whereIn('customer_id', $userIds)
                    ->where('is_deferred', 0)
                    ->get();

                $dueTotals = $sales
                    ->groupBy('customer_id')
                    ->map(fn($items) => round((float) $items->sum(fn($sale) => (float) $sale->due_amount), 2))
                    ->toArray();
            }

            if ($userIds->isNotEmpty() && $this->type === 'supplier') {
                $purchases = Purchase::with(['purchaseItems', 'expenses'])
                    ->whereIn('supplier_id', $userIds)
                    ->where('is_deferred', 0)
                    ->get();

                $dueTotals = $purchases
                    ->groupBy('supplier_id')
                    ->map(fn($items) => round((float) $items->sum(fn($purchase) => (float) $purchase->due_amount), 2))
                    ->toArray();
            }
        }

        return layoutView('users.users-list', get_defined_vars())
            ->title(__('general.titles.' . $this->type . 's'));
    }
}
