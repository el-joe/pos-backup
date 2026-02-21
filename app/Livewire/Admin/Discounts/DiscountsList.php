<?php

namespace App\Livewire\Admin\Discounts;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\BranchService;
use App\Services\DiscountService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class DiscountsList extends Component
{

    use LivewireOperations, WithPagination;
    private $discountService, $branchService;
    public $current;
    public $data = [
        'active' => false
    ];

    public $export;
    public $filters = [];
    public $collapseFilters = false;

    public $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:255',
        'type' => 'required|string|in:fixed,rate',
        'value' => 'required|numeric',
        'max_discount_amount' => 'nullable|numeric',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'usage_limit' => 'nullable|integer',
        'branch_id' => 'nullable|exists:branches,id',
        'sales_threshold' => 'nullable|numeric',
    ];

    function boot() {
        $this->discountService = app(DiscountService::class);
        $this->branchService = app(BranchService::class);
    }

    function setCurrent($id) {
        $this->current = $this->discountService->find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }else{
            $this->reset('data');
        }
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        AuditLog::log(AuditLogActionEnum::DELETE_DISCOUNT_TRY, ['id' => $id]);

        $this->confirm(
            'delete',
            'warning',
            __('general.messages.are_you_sure'),
            __('general.messages.confirm_delete_discount'),
            __('general.messages.yes_delete_it')
        );
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', __('general.messages.discount_not_found'));
            return;
        }

        $id = $this->current->id;

        $this->discountService->delete($id);

        AuditLog::log(AuditLogActionEnum::DELETE_DISCOUNT, ['id' => $id]);

        $this->popup('success', __('general.messages.discount_deleted_successfully'));

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save() {
        if (!$this->validator()) return;

        if($this->current){
            $action = AuditLogActionEnum::UPDATE_DISCOUNT;
        }else{
            $action = AuditLogActionEnum::CREATE_DISCOUNT;
        }
        try{
            DB::beginTransaction();
            $discount = $this->discountService->save($this->current?->id, $this->data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->popup('error', __('general.messages.error_saving_discount', ['message' => $e->getMessage()]));
            return;
        }

        AuditLog::log($action, ['id' => $discount->id]);

        $this->popup('success', __('general.messages.discount_saved_successfully'));

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        if ($this->export == 'excel') {
            $discounts = $this->discountService->list(filter : $this->filters , orderByDesc: 'id');

            $data = $discounts->map(function ($discount, $loop) {
                #	Name	Code	Value	Start Date	End Date	Status
                return [
                    'loop' => $loop + 1,
                    'name' => $discount->name,
                    'code' => $discount->code,
                    'value' => $discount->type == 'fixed' ? number_format($discount->value, 2) : (number_format($discount->value, 2) . '%'),
                    'start_date' => $discount->start_date ? carbon($discount->start_date)->format('Y-m-d') : 'N/A',
                    'end_date' => $discount->end_date ? carbon($discount->end_date)->format('Y-m-d') : 'N/A',
                    'status' => $discount->active ? 'Active' : 'Inactive',
                ];
            })->toArray();
            $columns = ['loop', 'name', 'code', 'value', 'start_date', 'end_date', 'status'];
            $headers = ['#', 'Name', 'Code', 'Value', 'Start Date', 'End Date', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'discounts');

            AuditLog::log(AuditLogActionEnum::EXPORT_DISCOUNTS, ['url' => $fullPath]);

            $this->redirectToDownload($fullPath);
        }

        $discounts = $this->discountService->list(perPage : 10 , orderByDesc: 'id', filter : $this->filters);
        $branches = $this->branchService->activeList();

        return layoutView('discounts.discounts-list', get_defined_vars())
            ->title(__('general.titles.discounts'));
    }
}
