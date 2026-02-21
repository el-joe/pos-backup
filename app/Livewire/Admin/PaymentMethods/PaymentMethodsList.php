<?php

namespace App\Livewire\Admin\PaymentMethods;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\BranchService;
use App\Services\PaymentMethodService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentMethodsList extends Component
{
    use LivewireOperations, WithPagination;
    private $paymentMethodService, $branchService;
    public $current;
    public $data = [
        'active' => false,
    ];

    public $rules = [
        'name' => 'required|string|max:255',
        'branch_id' => 'nullable',
    ];

    public $collapseFilters = false;
    public $filters = [];
    public $export;

    function boot() {
        $this->paymentMethodService = app(PaymentMethodService::class);
        $this->branchService = app(BranchService::class);
    }

    function mount(){
        if(admin()->branch_id){
            $this->data['branch_id'] = admin()->branch_id;
        }
    }

    function setCurrent($id) {
        $this->current = $this->paymentMethodService->find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->current['active'];
        }else{
            $this->reset('data');
        }
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        AuditLog::log(AuditLogActionEnum::DELETE_PAYMENT_METHOD_TRY, ['id' => $id]);

        $this->confirm(
            'delete',
            'warning',
            __('general.messages.are_you_sure'),
            __('general.messages.confirm_delete_payment_method'),
            __('general.messages.yes_delete_it')
        );
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', __('general.messages.payment_method_not_found'));
            return;
        }

        $id = $this->current->id;

        $this->paymentMethodService->delete($id);

        AuditLog::log(AuditLogActionEnum::DELETE_PAYMENT_METHOD, ['id' => $id]);

        $this->popup('success', __('general.messages.payment_method_deleted_successfully'));

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save() {
        if (!$this->validator()) return;

        if($this->current){
            $action = AuditLogActionEnum::UPDATE_PAYMENT_METHOD;
        }else{
            $action = AuditLogActionEnum::CREATE_PAYMENT_METHOD;
        }

        try{
            DB::beginTransaction();
            $paymentMethod = $this->paymentMethodService->save($this->current?->id, $this->data);
            AuditLog::log($action, ['id' => $paymentMethod->id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->popup('error', __('general.messages.error_saving_payment_method', ['message' => $e->getMessage()]));
            return;
        }


        $this->popup('success', __('general.messages.payment_method_saved_successfully'));

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        if ($this->export == 'excel') {
            $paymentMethods = $this->paymentMethodService->list(relations: [], filter: $this->filters, orderByDesc: 'id');

            $data = $paymentMethods->map(function ($paymentMethod, $loop) {
                return [
                    'loop' => $loop + 1,
                    'name' => $paymentMethod->name,
                    'branch' => $paymentMethod->branch?->name,
                    'active' => $paymentMethod->active ? 'Active' : 'Inactive',
                ];
            })->toArray();

            $columns = ['loop', 'name', 'branch', 'active'];
            $headers = ['#', 'Name', 'Branch', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'payment-methods');

            AuditLog::log(AuditLogActionEnum::EXPORT_PAYMENT_METHODS, ['url' => $fullPath]);

            $this->redirectToDownload($fullPath);
        }

        $paymentMethods = $this->paymentMethodService->list(relations: [], filter: $this->filters, perPage: 10, orderByDesc: 'id');
        $branches = $this->branchService->activeList();

        return layoutView('payment-methods.payment-methods-list', get_defined_vars())
            ->title(__( 'general.titles.payment-methods' ));
    }
}
