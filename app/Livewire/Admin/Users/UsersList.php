<?php

namespace App\Livewire\Admin\Users;

use App\Services\UserService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use LivewireOperations, WithPagination;
    private $userService;
    public $current;
    public $data = [];

    #[Url]
    public $type;

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

    function setCurrent($id) {
        $this->current = $this->userService->find($id);

        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }else{
            $this->data = [];
        }
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this user', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'User not found');
            return;
        }

        $this->userService->delete($this->current->id);

        $this->popup('success', 'User deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
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
    }
    public function render()
    {
        $users = $this->userService->list(perPage : 10 , orderByDesc: 'id',filter : ['type' => $this->type]);

        return layoutView('users.users-list', get_defined_vars())
            ->title(__('general.titles.' . $this->type . 's'));
    }
}
