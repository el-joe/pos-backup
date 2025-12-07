<?php

namespace App\Livewire\Central\CPanel\Admins;

use App\Models\Admin;
use App\Models\Subscription;
use App\Services\AdminService;
use App\Services\BranchService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.cpanel')]
class AdminsList extends Component
{
    use LivewireOperations, WithPagination;

    private $adminService, $branchService;
    public $current;
    public $data = [];

    public $collapseFilters = false;
    public $filters = [];
    public $export = null;

    public $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:admins,email|max:255',
        'password' => 'required|string|max:500',
    ];

    function setCurrent($id)
    {
        $this->current = Admin::find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            unset($this->data['password']);
            $this->data['active'] = (bool)$this->data['active'];
        }

        $this->dispatch('iCheck-load');
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this Admin', 'Yes, delete it!');
    }

    function delete()
    {
        if (!$this->current) {
            $this->popup('error', 'Admin not found');
            return;
        }

        $this->current->delete();

        $this->popup('success', 'Admin deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save()
    {
        if ($this->current) {
            $this->rules['email'] = 'required|email|max:255|unique:admins,email,' . $this->current->id;
            $this->rules['password'] = 'nullable|string|max:500';

            $admin = $this->current;
        } else {
            $admin = new Admin();
        }

        if (!$this->validator()) return;

        $admin = $admin->fill($this->data);
        $admin->save();

        $this->popup('success', 'Admin saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $admins = Admin::paginate(10);

        return view('livewire.central.cpanel.admins.admins-list', get_defined_vars());
    }
}
