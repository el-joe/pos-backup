<?php

namespace App\Livewire\Admin\Users;

use App\Services\UserService;
use App\Traits\LivewireOperations;
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

        $this->userService->save($this->current?->id, $this->data + ['type' => $this->type]);

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
