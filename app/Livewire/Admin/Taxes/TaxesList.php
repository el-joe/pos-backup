<?php

namespace App\Livewire\Admin\Taxes;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\TaxService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class TaxesList extends Component
{
    use LivewireOperations, WithPagination;
    private $taxService;
    public $current;
    public $data = [
        'active' => false
    ];

    public $filters = [];
    public $export;
    public $collapseFilters = false;

    public $rules = [
        'name' => 'required|string|max:255',
        'rate' => 'required|numeric',
        'vat_number' => 'nullable|string|max:255',
        'active' => 'boolean',
    ];

    function boot() {
        $this->taxService = app(TaxService::class);
    }

    function setCurrent($id) {
        $this->current = $this->taxService->find($id);
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

        AuditLog::log(AuditLogActionEnum::DELETE_TAX_TRY, ['id' => $id]);

        $this->confirm(
            'delete',
            'warning',
            __('general.messages.are_you_sure'),
            __('general.messages.confirm_delete_tax'),
            __('general.messages.yes_delete_it')
        );
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', __('general.messages.tax_not_found'));
            return;
        }

        $id = $this->current->id;

        $this->taxService->delete($id);

        AuditLog::log(AuditLogActionEnum::DELETE_TAX, ['id' => $id]);

        $this->popup('success', __('general.messages.tax_deleted_successfully'));

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save() {
        if (!$this->validator()) return;

        if($this->current) {
            $action = AuditLogActionEnum::UPDATE_TAX;
        } else {
            $action = AuditLogActionEnum::CREATE_TAX;
        }

        try{
            DB::beginTransaction();
            $tax = $this->taxService->save($this->current?->id, $this->data);

            AuditLog::log($action, ['id' => $tax->id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->popup('error', __('general.messages.error_saving_tax', ['message' => $e->getMessage()]));
            return;
        }

        $this->popup('success', __('general.messages.tax_saved_successfully'));

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        if ($this->export == 'excel') {
            $taxes = $this->taxService->list(filter: $this->filters, orderByDesc: 'id');

            $data = $taxes->map(function ($tax, $loop) {
                #	Name	Percentage	Status
                return [
                    'loop' => $loop + 1,
                    'name' => $tax->name,
                    'vat_number' => $tax->vat_number,
                    'rate' => $tax->rate,
                    'active' => $tax->active ? 'Active' : 'Inactive',
                ];
            })->toArray();
            $columns = ['loop', 'name', 'vat_number', 'rate', 'active'];
            $headers = ['#', 'Name', 'VAT Number', 'Percentage', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'taxes');

            AuditLog::log(AuditLogActionEnum::EXPORT_TAXES, ['url' => $fullPath]);

            $this->redirectToDownload($fullPath);
        }

        $taxes = $this->taxService->list(perPage: 10, orderByDesc: 'id', filter: $this->filters);

        return layoutView('taxes.taxes-list', get_defined_vars())
            ->title(__('general.titles.taxes'));
    }
}
