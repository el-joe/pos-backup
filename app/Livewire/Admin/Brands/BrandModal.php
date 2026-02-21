<?php

namespace App\Livewire\Admin\Brands;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\BrandService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class BrandModal extends Component
{
    use LivewireOperations, WithPagination;
    private $brandService;
    public $current;
    public $data = [
        'active' => false
    ];

    public $rules = [
        'name' => 'required|string|max:255',
        'active' => 'boolean',
    ];

    function boot() {
        $this->brandService = app(BrandService::class);
    }

    #[On('brand-set-current')]
    function setCurrent($id = null) {
        $this->current = $this->brandService->find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }else{
            $this->reset('data');
        }
    }

    public function save() {
        if (!$this->validator()) return;

        if($this->current){
            $action = AuditLogActionEnum::UPDATE_BRAND;
        }else{
            $action = AuditLogActionEnum::CREATE_BRAND;
        }

        try{
            DB::beginTransaction();
            $brand = $this->brandService->save($this->current?->id, $this->data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->popup('error', __('general.messages.error_saving_brand', ['message' => $e->getMessage()]));
            return;
        }


        AuditLog::log($action, ['id' => $brand->id]);

        $this->popup('success', __('general.messages.brand_saved_successfully'));

        $this->dismiss();

        $this->reset('current', 'data');

        $this->dispatch('re-render');
    }


    public function render()
    {
        return view('livewire.admin.brands.brand-modal');
    }
}
