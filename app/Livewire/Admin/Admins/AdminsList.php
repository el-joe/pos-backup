<?php

namespace App\Livewire\Admin\Admins;

use App\Services\AdminService;
use App\Services\BranchService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin')]
class AdminsList extends Component
{
    use LivewireOperations,WithPagination;

    private $adminService, $branchService;
    public $current;
    public $data = [];

    public $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:admins,email|max:255',
        'phone' => 'required|string|unique:admins,phone|max:50',
        'password' => 'required|string|max:500',
        'type' => 'required|string|in:super_admin,admin|max:255',
    ];

    function boot() {
        $this->adminService = app(AdminService::class);
        $this->branchService = app(BranchService::class);
    }

    function setCurrent($id) {
        $this->current = $this->adminService->find($id);
        if($this->current) {
            $this->data = $this->current->toArray();
            unset($this->data['password']);
            $this->data['active'] = (bool)$this->data['active'];
            $this->data['role_id'] = $this->current->roles->first()?->id;
        }

        $this->dispatch('iCheck-load');
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete','warning','Are you sure?','You want to delete this Admin','Yes, delete it!');
    }

    function delete() {
        if(!$this->current) {
            $this->popup('error','Admin not found');
            return;
        }

        $this->adminService->delete($this->current->id);

        $this->popup('success','Admin deleted successfully');

        $this->dismiss();

        $this->reset('current','data');
    }

    function save() {
        if($this->current){
            $this->rules['email'] = 'required|email|max:255|unique:admins,email,'.$this->current->id;
            $this->rules['phone'] = 'required|string|max:50|unique:admins,phone,'.$this->current->id;
            $this->rules['password'] = 'nullable|string|max:500';
        }

        if(!$this->validator())return;

        $admin = $this->adminService->save($this->current?->id,$this->data);

        if($this->data['role_id']??false){
            $role = Role::find($this->data['role_id']);
            $admin->syncRoles([$role->name]);
        }

        $this->popup('success','Admin saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $admins = $this->adminService->activeList();
        $branches = $this->branchService->activeList();
        $roles = Role::whereActive(1)->get();
        return view('livewire.admin.admins.admins-list',get_defined_vars());
    }
}
