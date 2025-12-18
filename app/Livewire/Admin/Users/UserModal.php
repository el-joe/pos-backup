<?php

namespace App\Livewire\Admin\Users;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\UserService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class UserModal extends Component
{
    use LivewireOperations;
    private $userService;
    public $current;
    public $type;
    public $data = [
        'active' => false
    ];

    public $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'active' => 'boolean',
        'type' => 'in:customer,supplier',
        'sales_threshold' => 'required_if:type,customer|numeric|min:0',
    ];

    function boot() {
        $this->userService = app(UserService::class);
    }

    #[On('user-set-current')]
    function setCurrent($id,$type = 'customer') {
        $this->current = $this->userService->find($id);
        $this->type = $type;
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }else{
            $this->reset('data');
        }
    }


    function save() {
        if($this->userService->checkIfUserExistsIntoSameType($this->data['email'] ?? '',$this->type) || $this->userService->checkIfUserExistsIntoSameType($this->data['phone'] ?? '',$this->type)) {
            $this->alert('error', 'User with this email or phone already exists');
            return;
        }
        if (!$this->validator()) return;

        if($this->current?->id){
            $action = AuditLogActionEnum::UPDATE_USER;
        }else{
            $action = AuditLogActionEnum::CREATE_USER;
        }

        try{
            DB::beginTransaction();
            $user = $this->userService->save($this->current?->id, $this->data + ['type' => $this->type]);

            AuditLog::log($action, ['id' => $user->id,'type' => $this->type]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->popup('error', 'Error occurred while saving user: ' . $e->getMessage());
            return;
        }

        $this->popup('success', 'User saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
        $this->dispatch('re-render');
    }

    public function render()
    {
        return view('livewire.admin.users.user-modal');
    }
}
