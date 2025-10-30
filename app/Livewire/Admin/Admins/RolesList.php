<?php

namespace App\Livewire\Admin\Admins;

use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin')]
class RolesList extends Component
{
    use LivewireOperations;
    public $current;

    #[Url]
    public $filter = [];

    #[Url]
    public $export;


    function setCurrent($id)
    {
        if (!$id) return;
        $this->current = Role::find($id);
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);
        $this->confirm('delete', 'error', 'Do you want to delete this feature?', '', 'Cancel');
    }

    function delete()
    {
        $this->current->delete();
        $this->popup('success', 'Feature deleted successfully', 'center');
    }
    public function render()
    {
        $roles = Role::all();
        return view('livewire.admin.admins.roles-list',get_defined_vars());
    }
}
